<?php

namespace App\Repository;

use App\Entity\CapteurBruit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository pour l'entité CapteurBruit
 * 
 * Gère l'accès aux données des capteurs de bruit installés dans la ville.
 * Permet de manipuler les informations relatives aux niveaux sonores mesurés
 * et aux seuils d'alerte paramétrés.
 */
class CapteurBruitRepository extends ServiceEntityRepository
{
    /**
     * Constructeur du repository
     * 
     * @param ManagerRegistry $registry Le registre de services Doctrine
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CapteurBruit::class);
    }

}
