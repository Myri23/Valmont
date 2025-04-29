<?php

namespace App\Repository;

use App\Entity\CapteurQualiteAir;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository pour l'entité CapteurQualiteAir
 * 
 * Gère l'accès aux données des capteurs de qualité de l'air.
 * Permet de manipuler les informations concernant les niveaux de polluants,
 * de particules fines et d'autres indicateurs de la qualité de l'air.
 */
class CapteurQualiteAirRepository extends ServiceEntityRepository
{
    /**
     * Constructeur du repository
     * 
     * @param ManagerRegistry $registry Le registre de services Doctrine
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CapteurQualiteAir::class);
    }
}
