<?php

namespace App\Repository;

use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository pour l'entité Utilisateur
 * 
 * Gère l'accès aux données des utilisateurs de l'application.
 * Permet de rechercher, filtrer et manipuler les informations
 * sur les utilisateurs selon différents critères.
 *
 * @extends ServiceEntityRepository<Utilisateur>
 */
class UtilisateurRepository extends ServiceEntityRepository
{
    /**
     * Constructeur du repository
     * 
     * @param ManagerRegistry $registry Le registre de services Doctrine
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateur::class);
    }

    /**
     * Trouve les utilisateurs selon leur rôle
     * 
     * @param string $role Le rôle à rechercher (pour 'administrateur')
     * @return array La liste des utilisateurs correspondants avec leurs informations de base
     */
    public function findByRole(string $role): array
    {
        $qb = $this->createQueryBuilder('u')
            ->select('u.email, u.prenom, u.nom')
            ->where('u.type_utilisateur = :type')
            ->setParameter('type', 'administrateur');
        
        return $qb->getQuery()->getResult();
    }
}
