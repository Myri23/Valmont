<?php

namespace App\Repository;

use App\Entity\PoubelleConnectee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository pour l'entité PoubelleConnectee
 * 
 * Gère l'accès aux données des poubelles connectées de la ville.
 * Permet de suivre le niveau de remplissage, les planifications
 * de collecte et les alertes liées aux poubelles intelligentes.
 */
class PoubelleConnecteeRepository extends ServiceEntityRepository
{
    /**
     * Constructeur du repository
     * 
     * @param ManagerRegistry $registry Le registre de services Doctrine
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PoubelleConnectee::class);
    }
}
