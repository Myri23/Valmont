<?php

namespace App\Repository;

use App\Entity\CodeVille;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CodeVille>
 *
 * @method CodeVille|null find($id, $lockMode = null, $lockVersion = null)
 * @method CodeVille|null findOneBy(array $criteria, array $orderBy = null)
 * @method CodeVille[]    findAll()
 * @method CodeVille[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CodeVilleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CodeVille::class);
    }

    public function save(CodeVille $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CodeVille $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Vérifie si un code de ville existe et n'est pas déjà utilisé
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
     * Récupère les statistiques des codes
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