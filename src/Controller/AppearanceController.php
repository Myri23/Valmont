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
    
    // Vérifier si le thème est "default" (par défaut)
    if ($config->getThemeName() === 'default') {
        // Pour le thème par défaut, ne générer AUCUN CSS personnalisé
    } else {
        // Couleur principale pour les éléments d'en-tête et d'interaction
        $css .= ".navbar, .header, .btn, button, input[type='submit'], .btn-primary, .btn-auth, .btn-search {\n";
        $css .= "    background-color: " . $config->getPrimaryColor() . " !important;\n";
        $css .= "    border-color: " . $config->getPrimaryColor() . " !important;\n";
        $css .= "    color: white !important;\n";
        $css .= "}\n\n";
        
        // PRÉSERVER LA VIDÉO - IMPORTANT
        // Ajouter ces règles AVANT les autres règles de fond
        $css .= ".hero-video {\n";
        $css .= "    background: none !important;\n";
        $css .= "    position: relative !important;\n";
        $css .= "    height: 80vh !important;\n";
        $css .= "    overflow: hidden !important;\n";
        $css .= "}\n\n";
        
        $css .= ".video-background {\n";
        $css .= "    position: absolute !important;\n";
        $css .= "    top: 0 !important;\n";
        $css .= "    left: 0 !important;\n";
        $css .= "    width: 100% !important;\n";
        $css .= "    height: 100% !important;\n";
        $css .= "    object-fit: cover !important;\n";
        $css .= "    z-index: -1 !important;\n";
        $css .= "    display: block !important;\n";
        $css .= "}\n\n";
        
        // Appliquer la couleur secondaire à tous les conteneurs SAUF la vidéo
        $css .= "body > div:not(.hero-video), section:not(.hero-video), article, .container-grid, .container-grid2, .hero:not(.hero-video), .container-lieu, .container-musees {\n";
        $css .= "    background-color: " . $config->getSecondaryColor() . " !important;\n";
        $css .= "}\n\n";
        
        // Pour la page d'admin 
        $css .= "body > div:not(.navbar):not(.hero-video), .admin-container, .admin-panel, #panneau-administration, #liste-utilisateurs {\n";
        $css .= "    background-color: " . $config->getSecondaryColor() . " !important;\n";
        $css .= "}\n\n";
        
        // Exceptions pour les cartes et éléments qui doivent rester blancs
        $css .= ".card, .card-lieu, .card-musees, .card-parcs, .card-resto, .card-biblio, .card-form, input[type='text'], input[type='email'], input[type='password'], textarea, select, .form-control {\n";
        $css .= "    background-color: #fff !important;\n";
        $css .= "}\n\n";
    }
    
    $response = new Response($css);
    $response->headers->set('Content-Type', 'text/css');
    $response->setMaxAge(5); // Cache court pour faciliter les tests
    return $response;
}
}
