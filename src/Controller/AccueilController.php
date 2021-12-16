<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Service\AlertServiceInterface;
use Doctrine\ORM\EntityManagerInterface;


class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'accueil')]
    public function index(ArticleRepository $articleRepository): Response
    {

        $articles = $articleRepository->findAll();

        return $this->render('accueil/index.html.twig', [
    
            'articles' => $articles
        ]);
    }

    #[Route('/', name: 'home')] 
    public function home() {
        return $this->render('accueil/home.html.twig');
    }


    #[Route('/accueil/new', name: 'accueil_create')]
    #[Route('/accueil/{id}/edit', name: 'accueil_edit')]

    public function form(Article $article = null, Request $request, EntityManagerInterface $manager){ //paramconverter:convertit un paramètre en une entité
        
        if(!$article){
            $article = new Article();
        }
     
        // $form = $this->createFormBuilder($article) //champs qui sont relié a une entité article 
        //              ->add('nom')
        //              ->add('description')
        //              ->add('prix')
        //              ->getForm();

        $form = $this->createForm(ArticleType::class, $article); 

        $form->handleRequest($request); //analyse de la requête

        if($form->isSubmitted() && $form->isValid()){ //si c'est submis et valide
        
            $manager->persist($article); //enregistrer dans la bdd
            $manager->flush();

            return $this->redirectToRoute('article_show', ['id'=> $article->getId()]); //redirection de la page d'article 
        }

        return $this->render('accueil/create.html.twig', [
            'formArticle' => $form->createView(), //form
            'editMode' =>$article->getId() !== null //si il est diff de null = true donc on sera en editMode
        ]);
    }


    #[Route('/accueil/article/{id}', name: 'article_show')]
    public function show(Article $article){

        return $this->render('accueil/show.html.twig', [
            'article' => $article ]);
    }

}
