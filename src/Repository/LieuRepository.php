<?php

namespace App\Repository;

use App\Entity\Lieu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Lieu>
 */
class LieuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lieu::class);
    }

    public function searchLieux($searchTerm): array
    {
        dump($searchTerm);
        $query = $this->createQueryBuilder('l')
            ->where('l.type LIKE :searchTerm')
            ->orWhere('l.nom LIKE :searchTerm')
            ->orWhere('l.description LIKE :searchTerm')
            ->orWhere('l.horaire LIKE :searchTerm')
            ->orWhere('l.acces LIKE :searchTerm')
            ->orWhere('l.menu LIKE :searchTerm')
            ->orWhere('l.livre LIKE :searchTerm')
            ->orWhere('l.auteur LIKE :searchTerm')

            ->setParameter('searchTerm', '%'.$searchTerm.'%')
            ->getQuery();

        dump($query->getDQL());
        return $query->getResult();
    }
}
