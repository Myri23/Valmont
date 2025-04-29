<?php

// src/Controller/HomeController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\ObjetConnecte;
use App\Form\ObjetConnecteType;
use App\Form\ModifierProfilType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Contrôleur pour la page d'accueil et fonctionnalités associées
 * 
 * Ce contrôleur gère la page d'accueil et certaines fonctionnalités
 * comme l'ajout d'objets connectés au système
 */
class HomeController extends AbstractController
{
    /**
     * Affiche la page d'accueil du site
     * 
     * Cette méthode récupère l'utilisateur connecté et
     * affiche la page d'accueil principale
     * 
     * @return Response La réponse HTTP
     */
    #[Route('/', name: 'home')]
    public function home()
    {
        /* Récupération de l'utilisateur connecté */    
        $user = $this->getUser();
        return $this->render('home/index.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * Gère l'ajout d'un nouvel objet connecté
     * 
     * @param Request $request La requête HTTP
     * @param EntityManagerInterface $em Gestionnaire d'entités Doctrine
     * @return Response La réponse HTTP
     */
    #[Route('/ajouter_objet', name: 'ajouter_objet')]
    public function ajouter(Request $request, EntityManagerInterface $em): Response
    {
        /* Création d'un nouvel objet connecté */    
        $objet = new ObjetConnecte();
        /* Création du formulaire associé à l'objet */        
        $form = $this->createForm(ObjetConnecteType::class, $objet);
        $form->handleRequest($request);

        /* Vérification si le formulaire est soumis et valide */
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($objet);
            $em->flush();
            /* Redirection vers la page d'ajout d'objet après soumission réussie */    
            return $this->redirectToRoute('ajouter_objet');
        }

        /* 
         * Affichage du formulaire si celui-ci n'est pas encore soumis
         * ou s'il contient des erreurs de validation
         */
        return $this->render('home/ajouter_objet.html.twig', [
            'form' => $form->createView(),
        ]);
    }  
}

