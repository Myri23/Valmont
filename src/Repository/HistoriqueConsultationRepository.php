<?php

namespace App\Repository;

use App\Entity\HistoriqueConsultation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository pour l'entité HistoriqueConsultation
 * 
 * Gère l'accès aux données des consultations effectuées par les utilisateurs.
 * Permet de suivre les pages et fonctionnalités consultées dans l'application.
 *
 * @extends ServiceEntityRepository<HistoriqueConsultation>
 *
 * @method HistoriqueConsultation|null find($id, $lockMode = null, $lockVersion = null)
 * @method HistoriqueConsultation|null findOneBy(array $criteria, array $orderBy = null)
 * @method HistoriqueConsultation[]    findAll()
 * @method HistoriqueConsultation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistoriqueConsultationRepository extends ServiceEntityRepository
{
    /**
     * Constructeur du repository
     * 
     * @param ManagerRegistry $registry Le registre de services Doctrine
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HistoriqueConsultation::class);
    }
}

