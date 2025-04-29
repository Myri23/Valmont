<?php

namespace App\Repository;

use App\Entity\HistoriqueConnexion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository pour l'entité HistoriqueConnexion
 * 
 * Gère l'accès aux données de l'historique des connexions utilisateurs.
 * Permet de suivre et d'analyser les accès à l'application.
 */
class HistoriqueConnexionRepository extends ServiceEntityRepository
{
    /**
     * Constructeur du repository
     * 
     * @param ManagerRegistry $registry Le registre de services Doctrine
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HistoriqueConnexion::class);
    }
}
