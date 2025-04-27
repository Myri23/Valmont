<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\IntegriteService;

class IntegriteController extends AbstractController
{
#[Route('/admin/integrite', name: 'admin_integrite_donnees')]
function index(IntegriteService $integriteService): Response
    {
        $resultats = $integriteService->verifierIntegrite();
        
        return $this->render('admin/integrite/index.html.twig', [
            'resultats' => $resultats,
        ]);
    }
    
#[Route('/admin/integrite/reparer/{type}', name: 'admin_integrite_reparer')]
    public function reparer(string $type, IntegriteService $integriteService): Response
    {
        $integriteService->reparer($type);
        $this->addFlash('success', 'Réparation effectuée avec succès');
        
        return $this->redirectToRoute('admin_integrite_donnees');
    }
}
