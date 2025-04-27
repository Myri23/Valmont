<?php

namespace App\Repository;

use App\Entity\HistoriqueConsultation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HistoriqueConsultation>
 *
 * @method HistoriqueConsultation|null find($id, $lockMode = null, $lockVersion = null)
 * @method HistoriqueConsultation|null findOneBy(array $criteria, array $orderBy = null)
 * @method HistoriqueConsultation[]    findAll()
 * @method HistoriqueConsultation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistoriqueConsultationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HistoriqueConsultation::class);
    }

    // Exemple de fonction personnalisée si tu veux plus tard :
    // public function findByUser($userId)
    // {
    //     return $this->createQueryBuilder('h')
    //         ->andWhere('h.utilisateur = :val')
    //         ->setParameter('val', $userId)
    //         ->orderBy('h.dateConsultation', 'DESC')
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }
}

