<?php

namespace App\Repository;

use App\Entity\CameraSurveillance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository pour l'entité CameraSurveillance
 * 
 * Gère l'accès aux données des caméras de surveillance de la ville connectée.
 * Permet de manipuler les informations relatives aux caméras, leurs caractéristiques
 * et leurs états de fonctionnement.
 */
class CameraSurveillanceRepository extends ServiceEntityRepository
{
    /**
     * Constructeur du repository
     * 
     * @param ManagerRegistry $registry Le registre de services Doctrine
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CameraSurveillance::class);
    }
}
