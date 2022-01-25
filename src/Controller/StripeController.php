<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Entity\State;
use App\Service\AlertServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class StripeController extends AbstractController
{

    private string $stripeApiSecretKey;
    private string $stripeCurrency;
    private string $stripeMode;

    public function __construct(string $stripeApiSecretKey, string $stripeCurrency, string $stripeMode)
    {
        $this->stripeApiSecretKey=$stripeApiSecretKey;
        $this->stripeCurrency=$stripeCurrency;
        $this->stripeMode=$stripeMode;
    }


    #[Route('/stripe', name: 'stripe')]
    public function index(EntityManagerInterface $entityManager, RequestStack $requestStack, SessionInterface $sessionInterface): Response
    {
        $products = [];
        $panier = $sessionInterface->get("panier");

        $order= new Order();
        $order->setUser($this->getUser());
        $order->setReference((new \DateTime('now'))->format('dmY').'-'.uniqid());
        $order->setState($entityManager->getReference(State::class, State::STATE_WAIT));

        $total = 0;
        foreach($panier as $article => $quantite)
        {
            $articleObjet=$entityManager->getRepository(Article::class)->find($article); 

            $orderDetail = new OrderDetail();
            $orderDetail ->setOrder($order);
            $orderDetail ->setPrice($articleObjet->getPrix());
            $orderDetail ->setQuantity($quantite);
            $totalArticle =$articleObjet->getPrix() * $quantite;
            $orderDetail ->setTotal($totalArticle);
            $orderDetail ->setArticle($articleObjet);
            
            $entityManager->persist($orderDetail);
            $order->addOrderDetail($orderDetail);

            $total += $totalArticle;
        }
        $order->setTotal($total);

        $entityManager->persist($order);
        $entityManager->flush();

        foreach ($order->getOrderDetails()->getValues() as $orderDetail) {
            $products[] = [
                'price_data'=> [
                    'currency'=> $this->stripeCurrency,
                    'unit_amount'=> $orderDetail->getPrice() * 100,
                    'product_data'=> [
                      'name'=> $orderDetail->getArticle()->getNom(),
                    ],
                ],
                'quantity'=> $orderDetail->getQuantity(),
            ];
        }
       
        Stripe::setApiKey($this->stripeApiSecretKey);

        $checkout_session = \Stripe\Checkout\Session::create([
            'customer_email' => $this->getUser()->getEmail(),
            'payment_method_types' => ['card'],
            'line_items'=> [$products],
            'mode'=> $this->stripeMode,
            'success_url'=> $this->generateUrl('stripe_success', ['id' => $order->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url'=> $this->generateUrl('stripe_cancel', ['id' => $order->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
       
        $this->updateOrder($order, $entityManager, $checkout_session);

        return new JsonResponse(['id' => $checkout_session->id]);
    }

    private function updateOrder(Order $order, EntityManagerInterface $entityManager, string $stripeSessionId): void
    {
        $order->setStripeSessionId($stripeSessionId);
        $entityManager->flush();
    }

    #[Route('/commande/success/{id}', name: 'stripe_success')]
    public function success(Order $order, AlertServiceInterface $alertService, EntityManagerInterface $entityManager, SessionInterface $sessionInterface)
    {

        if ($order->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('home');
        }

        //Modifie le statut isPaid de notre commande en mettant le status 1
        if ($order->getState()->getCode() === State::STATE_PAID) {
            $alertService->warning('Votre commande est déjà payé');
            return $this->redirectToRoute('accueil');
        }
    
       $sessionInterface->remove("panier");

        $order->setState($entityManager->getReference(State::class, State::STATE_PAID));
    
        $entityManager->flush();
       
        //Afficher les quelques informations de la commande de l'utilisateur
        return $this->render('stripe/success.html.twig', [
            'order' => $order,
        ]);
    }

    #[Route('/commande/cancel/{id}', name: 'stripe_cancel')]
    public function cancel(Order $order, EntityManagerInterface $entityManager)
    {

        if ($order->getUser() !== $this->getUser()){
            return $this->redirectToRoute('home');
        }

        $order->setState($entityManager->getReference(State::class, State::STATE_INCOMPLETE));
    
        $entityManager->flush();

        return $this->render('stripe/cancel.html.twig', [
            'order' => $order
        ]);
    }
}