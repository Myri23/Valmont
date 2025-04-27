<?php

// src/Controller/AppearanceController.php
namespace App\Controller;

use App\Entity\AppearanceConfig;
use App\Form\AppearanceConfigType;
use App\Repository\AppearanceConfigRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/appearance')]
class AppearanceController extends AbstractController
{
    #[Route('/', name: 'admin_appearance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager, AppearanceConfigRepository $repository): Response
    {
        // Récupérer la configuration existante ou en créer une nouvelle
        $config = $repository->findOneBy([]) ?? new AppearanceConfig();
        
        $form = $this->createForm(AppearanceConfigType::class, $config);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer et traiter les valeurs pour moduleLayout qui ne sont pas directement mappées
            $moduleLayout = $config->getModuleLayout();
            
            $moduleLayout['information']['enabled'] = $form->get('informationModuleEnabled')->getData();
            $moduleLayout['information']['order'] = $form->get('informationModuleOrder')->getData();
            // Faites de même pour les autres modules
            
            $config->setModuleLayout($moduleLayout);
            
            $entityManager->persist($config);
            $entityManager->flush();
            
            $this->addFlash('success', 'Les paramètres d\'apparence ont été mis à jour.');
            
            return $this->redirectToRoute('admin_appearance_edit');
        }
        
        return $this->render('appearance/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
// src/Controller/AppearanceController.php
// Ajoutez cette méthode à votre contrôleur

#[Route('/css/theme.css', name: 'admin_dynamic_css')]
public function generateCss(AppearanceConfigRepository $repository): Response
{
    $config = $repository->findOneBy([]) ?? new AppearanceConfig();
    
    $css = "/* CSS généré dynamiquement */\n\n";
    $css .= "body {\n";
    $css .= "    font-family: Arial, sans-serif;\n";
    $css .= "    margin: 0;\n";
    $css .= "    padding: 0;\n";
    $css .= "    background-color: " . $config->getPrimaryColor() . ";\n";
    $css .= "    color: #2F3D46;\n";
    $css .= "    line-height: 1.6;\n";
    $css .= "    overflow-y: scroll;\n";
    $css .= "}\n\n";
    
    $css .= ".navbar, footer {\n";
    $css .= "    background-color: " . $config->getSecondaryColor() . ";\n";
    $css .= "    color: white;\n";
    $css .= "}\n\n";
    
    // Ajoutez d'autres règles CSS basées sur votre configuration...
    
    $response = new Response($css);
    $response->headers->set('Content-Type', 'text/css');
    
    return $response;
}
}
