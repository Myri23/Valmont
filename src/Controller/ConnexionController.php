<?php
// src/Controller/ConnexionController.php
namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\ConnexionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Contrôleur pour l'authentification des utilisateurs
 * 
 * Ce contrôleur gère l'affichage du formulaire de connexion,
 * le traitement de la connexion et la déconnexion des utilisateurs
 */
class ConnexionController extends AbstractController
{
    /**
     * Affiche le formulaire de connexion
     * 
     * Cette méthode récupère les éventuelles erreurs d'authentification
     * et affiche le formulaire de connexion
     * 
     * @param AuthenticationUtils $authenticationUtils Utilitaires d'authentification Symfony
     * @return Response La réponse HTTP
     */
    #[Route('/connexion', name: 'connexion')]
    public function connexion(AuthenticationUtils $authenticationUtils): Response
    {
        /* Récupération de l'erreur de connexion s'il y en a une */
        $error = $authenticationUtils->getLastAuthenticationError();
        
        /* Récupération du dernier nom d'utilisateur saisi */
        $lastUsername = $authenticationUtils->getLastUsername();
        
        return $this->render('visualisation/connexion.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /* Point d'entrée pour la vérification des identifiants */
    #[Route('/connexion_check', name: 'connexion_check')]
    public function check()
    {
        // Cette méthode sera interceptée par le firewall
        throw new \LogicException('Cette méthode ne doit jamais être appelée directement.');
    }

    /**
     * Point d'entrée pour la déconnexion
     * 
     * Cette méthode est utilisée comme point d'entrée pour le processus
     * de déconnexion
     */
    #[Route('/deconnexion', name: 'deconnexion')]
    public function deconnexion(): void
    {
        throw new \LogicException('Cette méthode ne doit jamais être appelée directement.');
    }
}
