<?php

namespace App\Repository;

use App\Entity\CodeVille;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository pour l'entité CodeVille
 * 
 * Gère l'accès et les opérations liées aux codes de ville utilisés pour
 * l'authentification ou l'enregistrement des utilisateurs par adresse.
 *
 * @method CodeVille|null find($id, $lockMode = null, $lockVersion = null)
 * @method CodeVille|null findOneBy(array $criteria, array $orderBy = null)
 * @method CodeVille[]    findAll()
 * @method CodeVille[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CodeVilleRepository extends ServiceEntityRepository
{
    /**
     * Constructeur du repository
     * 
     * @param ManagerRegistry $registry Le registre de services Doctrine
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CodeVille::class);
    }

    /**
     * Persiste une entité CodeVille en base de données
     * 
     * @param CodeVille $entity L'entité à sauvegarder
     * @param bool $flush Indique si l'EntityManager doit être vidé immédiatement
     */
    public function save(CodeVille $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Supprime une entité CodeVille de la base de données
     * 
     * @param CodeVille $entity L'entité à supprimer
     * @param bool $flush Indique si l'EntityManager doit être vidé immédiatement
     */
    public function remove(CodeVille $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Vérifie si un code de ville existe et n'est pas déjà utilisé
     * 
     * @param string $code Le code à vérifier
     * @param string $adresse L'adresse associée au code
     * @return bool True si le code est valide et disponible, false sinon
     */
    public function isCodeValide(string $code, string $adresse): bool
    {
        $code = $this->findOneBy([
            'code' => $code,
            'adresse' => $adresse,
            'est_utilise' => false
        ]);

        return $code !== null;
    }

    /**
     * Marque un code comme utilisé et lui associe un utilisateur
     * 
     * @param string $code Le code à marquer comme utilisé
     * @param int $utilisateurId L'ID de l'utilisateur qui utilise le code
     * @return bool True si l'opération a réussi, false sinon
     */
    public function utiliserCode(string $code, int $utilisateurId): bool
    {
        $codeVille = $this->findOneBy([
            'code' => $code,
            'est_utilise' => false
        ]);

        if (!$codeVille) {
            return false;
        }

        $codeVille->setEstUtilise(true);
        $codeVille->setUtilisateurId($utilisateurId);
        $this->getEntityManager()->flush();

        return true;
    }

    /**
     * Récupère les statistiques d'utilisation des codes
     * 
     * Calcule le nombre total de codes, le nombre de codes utilisés,
     * le nombre de codes disponibles et leur répartition par quartier
     * 
     * @return array Tableau associatif des statistiques d'utilisation
     */
    public function getStatistiques(): array
    {
        $total = $this->count([]);
        $utilises = $this->count(['est_utilise' => true]);
        $disponibles = $total - $utilises;

        $quartiers = $this->createQueryBuilder('c')
            ->select('c.quartier, COUNT(c.id) as nombre')
            ->groupBy('c.quartier')
            ->getQuery()
            ->getResult();

        return [
            'total' => $total,
            'utilises' => $utilises,
            'disponibles' => $disponibles,
            'quartiers' => $quartiers
        ];
    }
}
