<?php

namespace App\Repository;

use App\Entity\Zone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Zone>
 */
class ZoneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Zone::class);
    }

    public function save(Zone $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Zone $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Trouver toutes les zones avec le nombre d'objets connectés
     */
    public function findAllWithObjectCount()
    {
        return $this->createQueryBuilder('z')
            ->select('z', 'COUNT(o.id) as objetCount')
            ->leftJoin('z.objetsConnectes', 'o')
            ->groupBy('z.id')
            ->getQuery()
            ->getResult();
    }
}