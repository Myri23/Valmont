<?php

namespace App\Repository;

use App\Entity\Lieu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository pour l'entité Lieu
 * 
 * Gère l'accès aux données des lieux d'intérêt de la ville.
 * Permet de rechercher et filtrer les différents lieux selon divers critères.
 *
 * @extends ServiceEntityRepository<Lieu>
 */
class LieuRepository extends ServiceEntityRepository
{
    /**
     * Constructeur du repository
     * 
     * @param ManagerRegistry $registry Le registre de services Doctrine
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lieu::class);
    }


    /**
     * Recherche des lieux selon un terme de recherche
     * 
     * Effectue une recherche multichamps (type, nom, description, horaire, etc.)
     * en normalisant les caractères accentués pour améliorer les résultats
     * 
     * @param string $searchTerm Le terme à rechercher
     * @return array La liste des lieux correspondant aux critères
     */
    public function searchLieux($searchTerm): array
    {
        // Directement enlever les accents sans passer par une fonction
        $searchTerm = \transliterator_transliterate('Any-Latin; Latin-ASCII', $searchTerm);

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
