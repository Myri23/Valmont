<?php

namespace App\Repository;

use App\Entity\ParkingIntelligent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository pour l'entité ParkingIntelligent
 * 
 * Gère l'accès aux données des parkings intelligents de la ville.
 */
class ParkingIntelligentRepository extends ServiceEntityRepository
{
    /**
     * Constructeur du repository
     * 
     * @param ManagerRegistry $registry Le registre de services Doctrine
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParkingIntelligent::class);
    }
}
