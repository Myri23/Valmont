<?php

namespace App\EventListener;

use App\Entity\HistoriqueConnexion;
use App\Entity\Utilisateur;
use App\Service\PointsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * Listener pour les événements de connexion réussie
 * 
 * Ce listener est déclenché lorsqu'un utilisateur se connecte avec succès.
 * Il enregistre l'historique de connexion et attribue des points à l'utilisateur
 * dans le cadre du système de gamification.
 */
class AuthentificationSuccessListener
{
    private EntityManagerInterface $entityManager;
    private RequestStack $requestStack;
    private PointsService $pointsService;

    /**
     * @param EntityManagerInterface $entityManager Gestionnaire d'entités Doctrine
     * @param RequestStack $requestStack Stack de requêtes pour accéder à la requête courante
     * @param PointsService $pointsService Service de gestion des points de gamification
     */
    public function __construct(
        EntityManagerInterface $entityManager, 
        RequestStack $requestStack,
        PointsService $pointsService
    ) {
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
        $this->pointsService = $pointsService;
    }


    /**
     * Méthode exécutée lors d'une connexion interactive réussie
     * 
     * @param InteractiveLoginEvent $event L'événement de connexion
     * @return void
     */
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
