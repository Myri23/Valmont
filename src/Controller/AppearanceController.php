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

/**
 * Contrôleur pour la personnalisation de l'apparence du site
 * 
 * Ce contrôleur permet aux administrateurs de personnaliser
 * l'apparence du site et de générer dynamiquement les CSS
 */
#[Route('/admin/appearance')]
class AppearanceController extends AbstractController
{
    /**
     * Affiche et traite le formulaire d'édition de l'apparence
     * 
     * @param Request $request Requête HTTP
     * @param EntityManagerInterface $entityManager Gestionnaire d'entités
     * @param AppearanceConfigRepository $repository Repository des configurations d'apparence
     * @return Response La réponse HTTP
     */
    #[Route('/', name: 'admin_appearance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager, AppearanceConfigRepository $repository): Response
    {
        /* Récupérer la configuration existante ou en créer une nouvelle */
        $config = $repository->findOneBy([]) ?? new AppearanceConfig();

        /* Création du formulaire basé sur la configuration */
        $form = $this->createForm(AppearanceConfigType::class, $config);
        
        /* Traitement de la requête pour remplir le formulaire */        
        $form->handleRequest($request);

        /* Vérification si le formulaire est soumis et valide */
        if ($form->isSubmitted() && $form->isValid()) {
            /* Récupération de la disposition des modules existante */
            $moduleLayout = $config->getModuleLayout();

            /* 
             * Mise à jour de la configuration des modules
             * avec les valeurs du formulaire
             */
            $moduleLayout['information']['enabled'] = $form->get('informationModuleEnabled')->getData();
            $moduleLayout['information']['order'] = $form->get('informationModuleOrder')->getData();

            /* Enregistrement de la nouvelle disposition des modules */
            $config->setModuleLayout($moduleLayout);
            
            /* Enregistrement de la configuration dans la base de données */            
            $entityManager->persist($config);
            $entityManager->flush();

            /* Message de confirmation pour l'administrateur */
            $this->addFlash('success', 'Les paramètres d\'apparence ont été mis à jour.');

            /* Redirection vers la même page pour voir les changements */
            return $this->redirectToRoute('admin_appearance_edit');
        }

        /* Affichage du formulaire d'édition */
        return $this->render('appearance/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Génère dynamiquement le CSS du thème
     * 
     * Cette méthode crée une feuille de style CSS basée sur
     * les paramètres d'apparence enregistrés dans la configuration
     * 
     * @param AppearanceConfigRepository $repository Repository des configurations d'apparence
     * @return Response La réponse HTTP contenant le CSS
     */
    #[Route('/css/theme.css', name: 'admin_dynamic_css')]
    public function generateCss(AppearanceConfigRepository $repository): Response
    {
        /* Récupérer la configuration ou utiliser les valeurs par défaut */    
        $config = $repository->findOneBy([]) ?? new AppearanceConfig();

        /* Initialisation du contenu CSS avec un commentaire */
        $css = "/* CSS généré dynamiquement */\n\n";
    
        /* Vérifier si le thème est "default" (par défaut) */
        if ($config->getThemeName() === 'default') {
            /* Pour le thème par défaut, ne générer AUCUN CSS personnalisé */
        } else {
            /* 
             * Génération des styles pour les éléments d'en-tête et d'interaction
             * en utilisant la couleur principale définie dans la configuration
             */
            $css .= ".navbar, .header, .btn, button, input[type='submit'], .btn-primary, .btn-auth, .btn-search {\n";
            $css .= "    background-color: " . $config->getPrimaryColor() . " !important;\n";
            $css .= "    border-color: " . $config->getPrimaryColor() . " !important;\n";
            $css .= "    color: white !important;\n";
            $css .= "}\n\n";
        
            /* 
             * PRÉSERVER LA VIDÉO - IMPORTANT
             * Ajouter ces règles AVANT les autres règles de fond
             * pour s'assurer que l'arrière-plan vidéo fonctionne correctement
             */
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
        
            /* 
             * Appliquer la couleur secondaire à tous les conteneurs
             * SAUF la vidéo pour maintenir le design cohérent
             */
            $css .= "body > div:not(.hero-video), section:not(.hero-video), article, .container-grid, .container-grid2, .hero:not(.hero-video), .container-lieu, .container-musees {\n";
            $css .= "    background-color: " . $config->getSecondaryColor() . " !important;\n";
            $css .= "}\n\n";
        
            /* 
             * Styles spécifiques pour la page d'administration
             * utilisant la couleur secondaire comme arrière-plan
             */
            $css .= "body > div:not(.navbar):not(.hero-video), .admin-container, .admin-panel, #panneau-administration, #liste-utilisateurs {\n";
            $css .= "    background-color: " . $config->getSecondaryColor() . " !important;\n";
            $css .= "}\n\n";
        
            /* 
             * Exceptions pour les cartes et éléments de formulaire
             * qui doivent conserver un fond blanc pour la lisibilité
             */
            $css .= ".card, .card-lieu, .card-musees, .card-parcs, .card-resto, .card-biblio, .card-form, input[type='text'], input[type='email'], input[type='password'], textarea, select, .form-control {\n";
            $css .= "    background-color: #fff !important;\n";
            $css .= "}\n\n";
        }
        /* 
         * Création de la réponse HTTP avec le CSS généré
         * et configuration des en-têtes appropriés
         */    
        $response = new Response($css);
        $response->headers->set('Content-Type', 'text/css');
        $response->setMaxAge(5); // Cache court pour faciliter les tests
        return $response;
    }
}
