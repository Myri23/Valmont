<?php

namespace App\EventListener;

use App\Entity\HistoriqueConnexion;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent; // <-- ici changement important

class AuthentificationSuccessListener
{
    private EntityManagerInterface $entityManager;
    private RequestStack $requestStack;

    public function __construct(
        EntityManagerInterface $entityManager, 
        RequestStack $requestStack
    ) {
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event): void  // <-- ici aussi changement important
    {
        $user = $event->getAuthenticationToken()->getUser();

        if (!$user instanceof Utilisateur) {
            return;
        }

        $historiqueConnexion = new HistoriqueConnexion();
        $historiqueConnexion->setUtilisateur($user);
        $historiqueConnexion->setDateConnexion(new \DateTime());

        $request = $this->requestStack->getCurrentRequest();
        if ($request) {
            $historiqueConnexion->setIpConnexion($request->getClientIp());
        }

        $this->entityManager->persist($historiqueConnexion);
        $this->entityManager->flush();
    }
}

