<?php

namespace App\Repository;

use App\Entity\ObjetConnecte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository pour l'entité ObjetConnecte
 * 
 * Gère l'accès aux données des objets connectés de la ville intelligente.
 * Classe principale qui regroupe tous les objets IoT du système.
 */
class ObjetConnecteRepository extends ServiceEntityRepository
{
    /**
     * Constructeur du repository
     * 
     * @param ManagerRegistry $registry Le registre de services Doctrine
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ObjetConnecte::class);
    }

    /**
     * Recherche des objets connectés selon un terme de recherche
     * 
     * @param string $searchTerm Le terme à rechercher
     * @return array La liste des objets connectés correspondant aux critères
     */
    public function searchObject($searchTerm): array
    {
        $searchTerm = \transliterator_transliterate('Any-Latin; Latin-ASCII', $searchTerm);

        dump($searchTerm);

        $query = $this->createQueryBuilder('o')
            ->where('o.nom LIKE :searchTerm')
            ->orWhere('o.type LIKE :searchTerm')
            ->orWhere('o.marque LIKE :searchTerm')
            ->orWhere('o.localisation LIKE :searchTerm')

            ->setParameter('searchTerm', '%'.$searchTerm.'%')
            ->getQuery();

        dump($query->getDQL());
        return $query->getResult();
    }
}

