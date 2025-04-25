<?php

namespace App\Repository;

use App\Entity\CapteurBruit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CapteurBruitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CapteurBruit::class);
    }

    // Vous pouvez ajouter ici vos requêtes personnalisées
}