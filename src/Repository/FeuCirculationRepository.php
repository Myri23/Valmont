<?php

namespace App\Repository;

use App\Entity\FeuCirculation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository pour l'entité FeuCirculation
 * 
 * Gère l'accès aux données des feux de circulation intelligents.
 * Permet de manipuler les informations sur l'état et le fonctionnement
 * des feux de circulation dans la ville connectée.
 */
class FeuCirculationRepository extends ServiceEntityRepository
{
    /**
     * Constructeur du repository
     * 
     * @param ManagerRegistry $registry Le registre de services Doctrine
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FeuCirculation::class);
    }
}
