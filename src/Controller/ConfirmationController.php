<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur pour la confirmation des comptes utilisateurs
 * 
 * Ce contrôleur gère la validation des comptes utilisateurs
 * après qu'ils aient cliqué sur le lien de confirmation dans leur email
 */
class ConfirmationController extends AbstractController
{
    /**
     * Confirme le compte d'un utilisateur avec un token de validation
     * 
     * @param string $token Le token de confirmation unique
     * @param EntityManagerInterface $entityManager Gestionnaire d'entités Doctrine
     * @return Response La réponse HTTP
     */
    #[Route('/confirm/{token}', name: 'confirm_account')]
    public function confirmAccount(string $token, EntityManagerInterface $entityManager): Response
    {
        /* Recherche de l'utilisateur correspondant au token */
        $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['confirmationToken' => $token]);

        /* Si aucun utilisateur trouvé, le token est invalide */
        if (!$user) {
            throw $this->createNotFoundException('Token de confirmation invalide.');
        }

        /* 
         * Validation du compte utilisateur :
         * - Marquer le compte comme confirmé
         * - Activer le compte
         * - Supprimer le token une fois utilisé pour des raisons de sécurité
         */
        $user->setIsConfirmed(true);
        $user->setCompteValide(true);
        $user->setConfirmationToken(null); // Supprimer le token une fois utilisé

        /* Enregistrement des modifications dans la base de données */        
        $entityManager->flush();

        /* Message de confirmation pour l'utilisateur */
        $this->addFlash('success', 'Votre compte a été confirmé avec succès ! Vous pouvez maintenant vous connecter.');

        /* Redirection vers la page de connexion */
        return $this->redirectToRoute('connexion');
    }
}
