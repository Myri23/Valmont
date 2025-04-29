<?php

// src/Repository/AppearanceConfigRepository.php
namespace App\Repository;

use App\Entity\AppearanceConfig;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository pour l'entité AppearanceConfig
 * 
 * Gère l'accès aux configurations d'apparence de l'interface utilisateur.
 * Permet de récupérer et modifier les paramètres visuels de l'application.
 */
class AppearanceConfigRepository extends ServiceEntityRepository
{
    /**
     * Constructeur du repository
     * 
     * @param ManagerRegistry $registry Le registre de services Doctrine
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppearanceConfig::class);
    }
}
