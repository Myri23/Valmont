<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConfirmationController extends AbstractController
{
    #[Route('/confirm/{token}', name: 'confirm_account')]
    public function confirmAccount(string $token, EntityManagerInterface $entityManager): Response
    {
        // Rechercher l'utilisateur par token
        $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['confirmationToken' => $token]);

        if (!$user) {
            throw $this->createNotFoundException('Token de confirmation invalide.');
        }

        // Valider le compte de l'utilisateur
        $user->setIsConfirmed(true);
        $user->setCompteValide(true);
        $user->setConfirmationToken(null); // Supprimer le token une fois utilisé
        
        $entityManager->flush();

        $this->addFlash('success', 'Votre compte a été confirmé avec succès ! Vous pouvez maintenant vous connecter.');
        
        return $this->redirectToRoute('connexion');
    }
}