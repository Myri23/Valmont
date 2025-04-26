<?php

namespace App\EventListener;

use App\Entity\HistoriqueConnexion;
use App\Entity\Utilisateur;
use App\Service\PointsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class AuthentificationSuccessListener
{
    private EntityManagerInterface $entityManager;
    private RequestStack $requestStack;
    private PointsService $pointsService;

    public function __construct(
        EntityManagerInterface $entityManager, 
        RequestStack $requestStack,
        PointsService $pointsService
    ) {
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
        $this->pointsService = $pointsService;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event): void
    {
        $user = $event->getAuthenticationToken()->getUser();

        if (!$user instanceof Utilisateur) {
            return;
        }

        // Enregistrer l'historique de connexion
        $historiqueConnexion = new HistoriqueConnexion();
        $historiqueConnexion->setUtilisateur($user);
        $historiqueConnexion->setDateConnexion(new \DateTime());

        $request = $this->requestStack->getCurrentRequest();
        if ($request) {
            $historiqueConnexion->setIpConnexion($request->getClientIp());
        }

        // Ajouter les points de connexion
        $this->pointsService->addConnectionPoints($user);

        // Persister les deux entités
        $this->entityManager->persist($historiqueConnexion);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
