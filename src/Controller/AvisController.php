<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\User;
use App\Repository\AvisRepository;
use App\Service\AlertServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AvisController extends AbstractController
{
    #[Route('/avis', name: 'avis')]
    #[IsGranted('ROLE_ADMIN', statusCode: 403, message: 'Accès refusé')]
    public function index(AvisRepository $avisRepository): Response
    {
     
        // if($this->isGranted(User::ROLE_ADMIN) === false ){
        //     throw new AccessDeniedException('Accès refusé');
        // }

        return $this->render('avis/index.html.twig', [
            'avis' => $avisRepository->findAll(),
        ]);
    }

    #[Route('/admin/actived-or-desactivated/{id}', name: 'avis_activedordesactivated')]
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
