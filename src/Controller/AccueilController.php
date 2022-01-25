<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;

class AccueilController extends AbstractController
{
    private const PAGE_RANGE = 12;
    /**
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    #[Route('/accueil', name: 'accueil')]
    public function index(ArticleRepository $articleRepository, PaginatorInterface $paginator, Request $request): Response
    {

        $pagination = $paginator->paginate(
            $articleRepository->getArticleQuery(), 
            $request->query->getInt('page', 1), /*page number*/
            self::PAGE_RANGE /*limit per page*/
        );

        return $this->render('accueil/index.html.twig', [
            'pagination' => $pagination,
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
