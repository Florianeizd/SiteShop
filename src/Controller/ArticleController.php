<?php

namespace App\Controller;

use App\Entity\Attachment;
use App\Service\AlertServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Article;
use App\Form\ArticleType;
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
    #[Route('/article/{id}/edit', name: 'article_edit')]
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
                /** @var UploadedFile $imageFile */
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

        return $this->render('article/create.html.twig', [
            'form' => $form->createView(), //form
        ]);
    }

    /**
     * @param Article $article
     * @return Response
     */
    #[Route('/accueil/article/{id}', name: 'article_show')]
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
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
