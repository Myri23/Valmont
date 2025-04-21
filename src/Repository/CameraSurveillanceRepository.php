<?php

namespace App\Repository;

use App\Entity\CameraSurveillance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CameraSurveillanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CameraSurveillance::class);
    }

    // Tu peux ajouter ici tes requêtes personnalisées
}
