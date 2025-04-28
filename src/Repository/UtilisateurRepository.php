<?php

namespace App\Repository;

use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Utilisateur>
 */
class UtilisateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateur::class);
    }


    public function findByRole(string $role): array
    {
        $qb = $this->createQueryBuilder('u')
            ->select('u.email, u.prenom, u.nom')
            ->where('u.type_utilisateur = :type')
            ->setParameter('type', 'administrateur');
        
        return $qb->getQuery()->getResult();
    }
}
