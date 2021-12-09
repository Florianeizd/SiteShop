<?php

namespace App\Controller;

use App\Form\InscriptionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;



class SecurityController extends AbstractController
{
    #[Route('/inscription', name: 'security_inscription')]
    public function inscription(Request $request,EntityManagerInterface $manager ){
        $user = new User();
        $form = $this->createForm(InscriptionType::class, $user);

        $form->handleRequest($request); //analyse de la requête

        if($form->isSubmitted() && $form->isValid()){ //si c'est submis et valide
        
            $manager->persist($user); //enregistrer dans la bdd
            $manager->flush();

        
        }

        return $this->render('security/inscription.html.twig', [
            'form' => $form->createView()
        ]);

    }
}
