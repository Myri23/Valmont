<?php

namespace App\Repository;

use App\Entity\AbribusIntelligent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AbribusIntelligentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AbribusIntelligent::class);
    }

    // Vous pouvez ajouter ici vos requêtes personnalisées
}