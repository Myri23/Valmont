<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository pour l'entité Event
 * 
 * Gère l'accès aux données des événements de la ville.
 * Permet de récupérer, rechercher et filtrer les événements.
 *
 * @extends ServiceEntityRepository<Event>
*/
class EventRepository extends ServiceEntityRepository
{
    /**
     * Constructeur du repository
     * 
     * @param ManagerRegistry $registry Le registre de services Doctrine
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * Recherche des événements selon un terme de recherche
     * 
     * @param string $searchTerm Le terme à rechercher
     * @return array La liste des événements correspondant aux critères
     */
    public function searchEvent($searchTerm): array
    {
    
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
