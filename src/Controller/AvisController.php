<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Repository\AvisRepository;
use App\Service\AlertServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AvisController extends AbstractController
{
    #[Route('/avis', name: 'avis')]
    public function index(AvisRepository $avisRepository): Response
    {
        return $this->render('avis/index.html.twig', [
            'avis' => $avisRepository->findAll(),
        ]);
    }

    #[Route('/actived-or-desactivated/{id}', name: 'avis_activedordesactivated')]
    public function activedordesactivated(Avis $avis, EntityManagerInterface $entityManagerInterface, AlertServiceInterface $alertServiceInterface): Response
    {   
        if($avis->isEnable()){
            $avis->setIsEnable(false);
        }
        else{
            $avis->setIsEnable(true);
        }

        $entityManagerInterface->persist($avis);
        $entityManagerInterface->flush();
        
        $alertServiceInterface->success('Avis mis a jour');

        return $this->redirectToRoute('avis');
    }
}
