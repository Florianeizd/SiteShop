<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Service\AlertServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cart', name: 'cart_')]
class CartController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(SessionInterface $sessionInterface, ArticleRepository $articleRepository): Response
    {
        $panier = $sessionInterface->get("panier", []);

        //On "fabrique" les données
        $dataPanier = [];
        $total=0;
        foreach($panier as $id => $quantite){
            $article = $articleRepository->find($id);
            $dataPanier[] = [
                "article" => $article,
                "quantite" => $quantite
            ];
            $total += $article->getPrix()* $quantite;
        }
      
        // $sessionInterface->set("panier", $dataPanier);

        return $this->render('cart/index.html.twig', compact("dataPanier", "total"));
    }

    #[Route('/add/{id}', name: 'add')]
    public function add(Article $article, SessionInterface $sessionInterface, AlertServiceInterface $alertServiceInterface) //stocker dans ma session le n° du produit et la quantité
    {
        //On récupère le panier actuel
        $panier = $sessionInterface->get("panier", []);
        $id = $article->getId();
        
        if(!empty ($panier[$id])){  //si c'est vide on l'incremente sinon on le créer
            $panier[$id]++;
        }
        else{
            $panier[$id] = 1;
        }

        //On sauvegarde dans la session
        $sessionInterface->set("panier", $panier);

        $alertServiceInterface->success('Article ajouter');

        return $this->redirectToRoute("cart_index");
    }

   

    #[Route('/remove/{id}', name: 'remove')]
    public function remove(Article $article, SessionInterface $sessionInterface, AlertServiceInterface $alertServiceInterface) //stocker dans ma session le n° du produit et la quantité
    {
        //On récupère le panier actuel
        $panier = $sessionInterface->get("panier", []);
        $id = $article->getId();
        
        if(!empty ($panier[$id])){  //si le panier n'est pas vide => si il est + grand que 1 on supp 1 quantité sinon on supprime toute la ligne
            if($panier[$id] >1){
                $panier[$id]--;
            }else{
                unset($panier[$id]);
            }
            
        }

        //On sauvegarde dans la session
        $sessionInterface->set("panier", $panier);

        $alertServiceInterface->success('Nombre d\'article modifier ou supprimer');
        
        return $this->redirectToRoute("cart_index");
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Article $article, SessionInterface $sessionInterface, AlertServiceInterface $alertServiceInterface) //stocker dans ma session le n° du produit et la quantité
    {
        //On récupère le panier actuel
        $panier = $sessionInterface->get("panier", []);
        $id = $article->getId();
        
        if(!empty ($panier[$id])){  //si le panier n'est pas vide on supprime toute la ligne
            unset($panier[$id]);
        }
            
        //On sauvegarde dans la session
        $sessionInterface->set("panier", $panier);

        $alertServiceInterface->success('Article supprimer');
        
        return $this->redirectToRoute("cart_index");
    } 
    
    
    #[Route('/deleteAll/panier', name: 'deleteAll', methods: ['GET'])]
    public function deleteAll(SessionInterface $session, AlertServiceInterface $alertServiceInterface)
    {
        $alertServiceInterface->success('Panier supprimer');
        $session->remove('panier');
        return $this->redirectToRoute('cart_index');
    }
}
