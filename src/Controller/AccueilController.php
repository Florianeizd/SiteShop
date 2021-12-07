<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'accueil')]
    public function index(ArticleRepository $repo): Response
    {

        $articles = $repo->findAll();

        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
            'articles' => $articles
        ]);
    }

    #[Route('/', name: 'home')] 
    public function home() {
        return $this->render('accueil/home.html.twig');
    }


    #[Route('/accueil/new', name: 'accueil_create')]
    public function create(Request $request){
        $article = new Article();

        $form = $this->createFormBuilder($article)
                     ->add('nom')
                     ->add('description')
                     ->add('prix')
                     ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // $manager->persist($article);
            // $manager->flush();

            // return $this->redirectToRoute('accueil_show', ['id'=> $article->getId()]);
        }

        return $this->render('accueil/create.html.twig', [
            'formArticle' => $form->createView()
        ]);
    }


    #[Route('/accueil/article/{id}', name: 'article_show')]
    public function show(Article $article){

        return $this->render('accueil/show.html.twig', [
            'article' => $article ]);
    }

    

}
