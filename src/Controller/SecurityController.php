<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Contrôleur gérant les fonctionnalités d'authentification.
 */
class SecurityController extends AbstractController
{
    /**
     * Affiche le formulaire de connexion et gère les erreurs d'authentification.
     *
     * @param AuthenticationUtils $authenticationUtils Utilitaires d'authentification
     * @return Response Réponse HTTP contenant le formulaire de connexion
     */
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Récupération de l'erreur de connexion s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();
        // Récupération du dernier nom d'utilisateur saisi
        $lastUsername = $authenticationUtils->getLastUsername();
        // Rendu du template de connexion avec les données nécessaires
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * Gère la déconnexion de l'utilisateur.
     */
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
