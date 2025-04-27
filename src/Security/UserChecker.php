<?php

namespace App\Security;

use App\Entity\Utilisateur;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof Utilisateur) {
            return;
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