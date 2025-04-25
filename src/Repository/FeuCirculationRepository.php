<?php

namespace App\Repository;

use App\Entity\FeuCirculation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class FeuCirculationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FeuCirculation::class);
    }

    // Ajoute ici tes méthodes personnalisées si besoin
}
