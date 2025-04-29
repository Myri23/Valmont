<?php

namespace App\Repository;

use App\Entity\AbribusIntelligent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository pour l'entité AbribusIntelligent
 * 
 * Gère l'accès aux données des abribus intelligents dans la base de données
 */
class AbribusIntelligentRepository extends ServiceEntityRepository
{
    /**
     * Constructeur du repository
     * 
     * @param ManagerRegistry $registry Le registre de services Doctrine
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AbribusIntelligent::class);
    }

}
