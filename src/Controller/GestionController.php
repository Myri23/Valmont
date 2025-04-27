<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class GestionController extends AbstractController
{
    #[Route('/gestion', name: 'gestion')]
    public function index(): Response
    {
        $utilisateur = $this->getUser();
        
        // Vérifier si l'utilisateur est connecté
        if (!$utilisateur) {
            $this->addFlash('error', 'Vous devez être connecté pour accéder à cette page.');
            return $this->redirectToRoute('connexion');
        }
        
        // Vérifier si l'utilisateur a les privilèges nécessaires
        if (!($utilisateur->getTypeUtilisateur() === 'complexe' || 
              $utilisateur->getTypeUtilisateur() === 'administrateur' ||
              $utilisateur->getNiveauExperience() === 'avance' || 
              $utilisateur->getNiveauExperience() === 'complexe')) {
            $this->addFlash('error', 'Vous n\'avez pas les autorisations nécessaires pour accéder à cette page.');
            return $this->redirectToRoute('home');
        }
        
        return $this->render('gestion/index.html.twig', [
            'utilisateur' => $utilisateur
        ]);
    }
}
