<?php
// src/Service/HistoriqueConsultationService.php

namespace App\Service;

use App\Entity\HistoriqueConsultation;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class HistoriqueConsultationService
{
    private EntityManagerInterface $entityManager;
    private Security $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function enregistrerConsultation(string $typeElement, int $elementId, string $nomElement = null): void
    {
        $user = $this->security->getUser();
        
        // Vérifier que l'utilisateur est connecté
        if (!$user instanceof Utilisateur) {
            return;
        }

        $consultation = new HistoriqueConsultation();
        $consultation->setUtilisateur($user);
        $consultation->setTypeElement($typeElement);
        $consultation->setElementId($elementId);
        $consultation->setNomElement($nomElement); // Enregistrez le nom
        $consultation->setDateConsultation(new \DateTimeImmutable());

        $this->entityManager->persist($consultation);
        $this->entityManager->flush();
    }
}
