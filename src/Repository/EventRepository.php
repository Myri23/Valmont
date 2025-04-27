<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function searchEvent($searchTerm): array
    {
        // Directement enlever les accents sans passer par une fonction
        $searchTerm = \transliterator_transliterate('Any-Latin; Latin-ASCII', $searchTerm);

        $query = $this->createQueryBuilder('e')
            ->where('e.type LIKE :searchTerm')
            ->orWhere('e.nom LIKE :searchTerm')
            ->orWhere('e.description LIKE :searchTerm')
            ->orWhere('e.date LIKE :searchTerm')

            ->setParameter('searchTerm', '%'.$searchTerm.'%')
            ->getQuery();

        dump($query->getDQL());
        return $query->getResult();
    }
}
