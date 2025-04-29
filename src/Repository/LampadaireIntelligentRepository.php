<?php

namespace App\Repository;

use App\Entity\LampadaireIntelligent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository pour l'entité LampadaireIntelligent
 * 
 * Gère l'accès aux données des lampadaires intelligents de la ville.
 * Permet de manipuler les informations sur l'état, la luminosité
 * et le fonctionnement des lampadaires connectés.
 */
class LampadaireIntelligentRepository extends ServiceEntityRepository
{
    /**
     * Constructeur du repository
     * 
     * @param ManagerRegistry $registry Le registre de services Doctrine
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LampadaireIntelligent::class);
    }
}
