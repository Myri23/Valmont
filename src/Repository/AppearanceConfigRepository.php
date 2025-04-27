<?php

// src/Repository/AppearanceConfigRepository.php
namespace App\Repository;

use App\Entity\AppearanceConfig;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AppearanceConfigRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppearanceConfig::class);
    }
}
