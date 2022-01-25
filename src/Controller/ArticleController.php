<?php

namespace App\Controller;

use App\Entity\Attachment;
use App\Entity\Avis;
use App\Service\AlertServiceInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Article;
use App\Form\ArticleType;
use App\Form\AvisType;
use App\Service\FileUploadServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

class ArticleController extends AbstractController
{
    private AlertServiceInterface $alertService;

    /**
     * @param AlertServiceInterface $alertService
     */
    public function __construct(AlertServiceInterface $alertService)
    {
        $this->alertService = $alertService;
    }

    /**
     * @param Article|null $article
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param FileUploadServiceInterface $fileUploadService
     * @return RedirectResponse|Response
     */
    #[Route('/article/new', name: 'article_create')]
    #[Route('/admin/article/{id}/edit', name: 'article_edit')]
    public function form(Article $article = null, Request $request, EntityManagerInterface $manager, FileUploadServiceInterface $fileUploadService): RedirectResponse|Response
    {
        if (!$article) {
            $article = new Article();
        }

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $attachments = $form->get('attachments')->getData();
            foreach ($attachments as $attachment) {
                /** @var UploadedFile $imageUploadedFile */
                $imageUploadedFile = $attachment->getFile();

                if ($imageUploadedFile) {
                    $attachmentObject = $fileUploadService->upload($attachment);
                    $article->addAttachment($attachmentObject);
                }
            }

            $manager->persist($article);
            $manager->flush();

            if (!$article) {
                $this->alertService->success('Article créer avec succès !');
            } else {
                $this->alertService->success('Article modifié avec succès !');
            }

            return $this->redirectToRoute('article_show', [
                'id'=> $article->getId(),
            ]);
        }

        return $this->render('article/form.html.twig', [
            'form' => $form->createView(), //form
        ]);
    }

    /**
     * @param Article $article
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    
    #[Route('/accueil/article/{id}', name: 'article_show')]
    public function show(Article $article, Request $request, EntityManagerInterface $manager): Response
    {
        //Partie avis
        //on crée le commentaire "vierge"
        $avis = new Avis;

        //on génère le formulaire
        $form = $this->createForm(AvisType::class, $avis);
        $form->handleRequest($request);

        //traitement du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            $avis->setArticle($article);

            $manager->persist($avis);
            $manager->flush();

            $this->alertService->success('Votre avis a bien été envoyé !');

            return $this->redirectToRoute('article_show', ['id' => $article->getId()]);
        }

        // Filtre sur les avis actifs (isEnable = 1) le plus jeune en premier
        $collectionAvis = new ArrayCollection(array_reverse($article->getAvis()->toArray()));
        $avis = $collectionAvis->filter(function ($avis) {
            /** @var $avis Avis */
           return $avis->isEnable();
        });

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'avis' => $avis,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Attachment $attachment
     * @return Response
     */
    #[Route('/article/download/attachment/{id}', name: 'article_download_attachment')]
    public function downloadAttachment(Attachment $attachment): Response
    {
        return $this->file('images/articles/' . $attachment->getName());
    }
}
