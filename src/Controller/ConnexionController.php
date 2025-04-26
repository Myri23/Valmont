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

class ConnexionController extends AbstractController
{
    #[Route('/connexion', name: 'connexion')]
    public function connexion(AuthenticationUtils $authenticationUtils): Response
    {
        // Récupérer l'erreur de connexion s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();
        
        // Dernier nom d'utilisateur saisi
        $lastUsername = $authenticationUtils->getLastUsername();
        
        return $this->render('home/connexion.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }
    
    #[Route('/connexion_check', name: 'connexion_check')]
    public function check()
    {
        // Cette méthode sera interceptée par le firewall
        throw new \LogicException('Cette méthode ne doit jamais être appelée directement.');
    }
    
    #[Route('/deconnexion', name: 'deconnexion')]
    public function deconnexion(): void
    {
        // Cette méthode sera interceptée par le firewall
        throw new \LogicException('Cette méthode ne doit jamais être appelée directement.');
    }


}