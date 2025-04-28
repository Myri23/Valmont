<?php

namespace App\Security;

use App\Entity\Utilisateur;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof Utilisateur) {
            return;
        }

        // Vérifier si le compte a été rejeté par l'administrateur
        if ($user->getStatutVerification() === 'rejete') {
            throw new CustomUserMessageAuthenticationException('Votre inscription a été refusée par l\'administration. Pour plus d\'informations, veuillez contacter le support.');
        }

        // Vérifier si le compte est en attente de vérification
        if ($user->getStatutVerification() === 'en_attente') {
            throw new CustomUserMessageAuthenticationException('Votre compte est en attente de vérification par l\'administration. Vous recevrez un email lorsque votre compte sera validé.');
        }

        if (!$user->isConfirmed()) {
            throw new CustomUserMessageAccountStatusException('Votre compte n\'est pas confirmé. Veuillez vérifier votre email pour le lien de confirmation ou demander un nouveau lien de confirmation.');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        // Vous pouvez ajouter d'autres vérifications après l'authentification si nécessaire
    }
}