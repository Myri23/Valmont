<?php

namespace App\Repository;

use App\Entity\LampadaireIntelligent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class LampadaireIntelligentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LampadaireIntelligent::class);
    }
}
