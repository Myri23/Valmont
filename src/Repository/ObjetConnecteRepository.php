<?php

namespace App\Repository;

use App\Entity\ObjetConnecte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ObjetConnecteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ObjetConnecte::class);
    }

    public function searchTransport($searchTerm): array
    {
        // Directement enlever les accents sans passer par une fonction
        $searchTerm = \transliterator_transliterate('Any-Latin; Latin-ASCII', $searchTerm);

        $query = $this->createQueryBuilder('o')
            ->where('t.type LIKE :searchTerm')
            ->orWhere('t.description LIKE :searchTerm')

            ->setParameter('searchTerm', '%'.$searchTerm.'%')
            ->getQuery();

        dump($query->getDQL());
        return $query->getResult();
    }
}

