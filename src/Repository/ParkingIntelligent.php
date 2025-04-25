<?php

namespace App\Repository;

use App\Entity\ParkingIntelligent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ParkingIntelligentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParkingIntelligent::class);
    }

    // Vous pouvez ajouter ici vos requêtes personnalisées
}