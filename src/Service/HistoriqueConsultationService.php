<?php
// src/Service/HistoriqueConsultationService.php

namespace App\Service;

use App\Entity\HistoriqueConsultation;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * Service de gestion du système de points
 * 
 * Ce service gère l'attribution de points aux utilisateurs pour différentes actions
 * (connexions, consultations) et la mise à jour de leur niveau d'expérience
 * en fonction de leur nombre total de points.
 */
class HistoriqueConsultationService
{

    private EntityManagerInterface $entityManager;
    private Security $security;

    /**
     * @param EntityManagerInterface $entityManager Gestionnaire d'entités Doctrine
     * @param Security $security Service de sécurité Symfony pour récupérer l'utilisateur courant
     */
    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    /**
     * Enregistre une consultation d'élément par l'utilisateur connecté
     * 
     * @param string $typeElement Type d'élément consulté (ex: 'article', 'document')
     * @param int $elementId Identifiant de l'élément consulté
     * @param string|null $nomElement Nom ou titre de l'élément consulté (optionnel)
     * @return void
     */
    public function enregistrerConsultation(string $typeElement, int $elementId, string $nomElement = null): void
    {
        $user = $this->security->getUser();
        
        if (!$user instanceof Utilisateur) {
            return;
        }

        $consultation = new HistoriqueConsultation();
        $consultation->setUtilisateur($user);
        $consultation->setTypeElement($typeElement);
        $consultation->setElementId($elementId);
        $consultation->setNomElement($nomElement);
        $consultation->setDateConsultation(new \DateTimeImmutable());

        $this->entityManager->persist($consultation);
        $this->entityManager->flush();
    }
}
