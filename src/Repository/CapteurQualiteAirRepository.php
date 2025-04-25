<?php

namespace App\Repository;

use App\Entity\CapteurQualiteAir;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CapteurQualiteAirRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CapteurQualiteAir::class);
    }

    // Ajoute ici tes méthodes personnalisées si besoin
}
