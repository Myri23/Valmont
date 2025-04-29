<?php

namespace App\Repository;

use App\Entity\Transport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository pour l'entité Transport
 * 
 * Gère l'accès aux données des moyens de transport de la ville connectée.
 * @extends ServiceEntityRepository<Transport>
 */
class TransportRepository extends ServiceEntityRepository
{
    /**
     * Constructeur du repository
     * 
     * @param ManagerRegistry $registry Le registre de services Doctrine
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transport::class);
    }

    /**
     * Recherche des transports selon un terme de recherche
     * 
     * @param string $searchTerm Le terme à rechercher
     * @return array La liste des transports correspondant aux critères
     */
    public function searchTransport($searchTerm): array
    {
        $searchTerm = \transliterator_transliterate('Any-Latin; Latin-ASCII', $searchTerm);

        $query = $this->createQueryBuilder('t')
            ->where('t.type LIKE :searchTerm')
            ->orWhere('t.description LIKE :searchTerm')

            ->setParameter('searchTerm', '%'.$searchTerm.'%')
            ->getQuery();

        dump($query->getDQL());
        return $query->getResult();
    }
}
