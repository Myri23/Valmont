<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
* Contrôleur pour l'accès aux fonctionnalités de gestion
* 
* Ce contrôleur vérifie les autorisations des utilisateurs
* avant de leur permettre d'accéder aux pages de gestion
*/
class GestionController extends AbstractController
{
   /**
    * Affiche la page d'accueil de l'interface de gestion
    * 
    * @return Response La réponse HTTP
    */
    #[Route('/gestion', name: 'gestion')]
    public function index(): Response
    {
        $utilisateur = $this->getUser();
        
       /* Vérification si l'utilisateur est connecté */
        if (!$utilisateur) {
            $this->addFlash('error', 'Vous devez être connecté pour accéder à cette page.');
            return $this->redirectToRoute('connexion');
        }
        
       
       /* 
        * Vérification des privilèges de l'utilisateur
        * Accès autorisé uniquement aux utilisateurs de type complexe ou administrateur
        * ou ayant un niveau d'expérience avancé ou complexe
        */
        if (!($utilisateur->getTypeUtilisateur() === 'complexe' || 
              $utilisateur->getTypeUtilisateur() === 'administrateur' ||
              $utilisateur->getNiveauExperience() === 'avance' || 
              $utilisateur->getNiveauExperience() === 'complexe')) {
            $this->addFlash('error', 'Vous n\'avez pas les autorisations nécessaires pour accéder à cette page.');
            return $this->redirectToRoute('home');
        }

       /* 
        * Affichage du template de l'interface de gestion
        * avec les informations de l'utilisateur
        */
        return $this->render('gestion/index.html.twig', [
            'utilisateur' => $utilisateur
        ]);
    }
}
