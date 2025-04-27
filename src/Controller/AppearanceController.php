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
    
    // Appliquer la couleur principale à certains éléments spécifiques
    $css .= ".navbar, header, .header, h1, h2, h3, h4, h5, h6, .btn, button, input[type='submit'], input[type='button'], .card-header {\n";
    $css .= "    background-color: " . $config->getPrimaryColor() . " !important;\n";
    $css .= "    border-color: " . $config->getPrimaryColor() . " !important;\n";
    $css .= "}\n\n";
    
    // Appliquer la couleur secondaire à TOUS les conteneurs
    $css .= "body, main, section, article, aside, div, footer, .card-body, .container, .content, .preview-box {\n";
    $css .= "    background-color: " . $config->getSecondaryColor() . " !important;\n";
    $css .= "}\n\n";
    
    // Exceptions pour les éléments qui doivent rester blancs
    $css .= "input[type='text'], input[type='email'], input[type='password'], textarea, select, .card, .white-bg, .form-control {\n";
    $css .= "    background-color: #fff !important;\n";
    $css .= "}\n\n";
    
    // S'assurer que le texte reste lisible
    $css .= ".navbar, header, .header, h1, h2, h3, h4, h5, h6, .btn, button, input[type='submit'], input[type='button'] {\n";
    $css .= "    color: #fff !important;\n";
    $css .= "}\n\n";
    
    $css .= "body, main, section, article, aside, div, footer, p, span, a {\n";
    $css .= "    color: #333 !important;\n";
    $css .= "}\n\n";
    
    $response = new Response($css);
    $response->headers->set('Content-Type', 'text/css');
    $response->setMaxAge(5); // Cache court pour faciliter les tests
    return $response;
}
}
