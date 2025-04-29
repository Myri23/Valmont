<?php

namespace App\Repository;

use App\Entity\BorneRecharge;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository pour l'entité BorneRecharge
 * 
 * Gère l'accès aux données des bornes de recharge électriques.
 * Permet de gérer les informations concernant l'état, la disponibilité
 * et les caractéristiques techniques des bornes de recharge.
 */
class BorneRechargeRepository extends ServiceEntityRepository
{
    /**
     * Constructeur du repository
     * 
     * @param ManagerRegistry $registry Le registre de services Doctrine
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BorneRecharge::class);
    }
}
