<?php

namespace App\Repository;

use App\Entity\ObjetConnecte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ObjetConnecteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ObjetConnecte::class);
    }

    // Ajoute tes méthodes personnalisées ici si besoin
}

