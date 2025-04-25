<?php

namespace App\Repository;

use App\Entity\PoubelleConnectee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PoubelleConnecteeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PoubelleConnectee::class);
    }
}
