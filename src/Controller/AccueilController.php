<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticleRepository;

class AccueilController extends AbstractController
{
    /**
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    #[Route('/accueil', name: 'accueil')]
    public function index(ArticleRepository $articleRepository): Response
    {

        $articles = $articleRepository->findAll();

        return $this->render('accueil/index.html.twig', [

            'articles' => $articles
        ]);
    }

    /**
     * @return Response
     */
    #[Route('/', name: 'home')]
    public function home() {
        return $this->render('accueil/home.html.twig');
    }
}
