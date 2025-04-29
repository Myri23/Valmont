<?php
// src/Service/PointsService.php
namespace App\Service;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Service de gestion du système de gamification par points
 * 
 * Ce service gère l'attribution de points aux utilisateurs pour différentes actions
 * (connexions, consultations) et la mise à jour de leur niveau d'expérience
 * en fonction de leur nombre total de points.
 */
class PointsService
{
    private EntityManagerInterface $entityManager;
    private const POINTS_CONNEXION = 0.25;
    private const POINTS_CONSULTATION = 0.50;
    
    // Définissez les seuils de niveaux
    private const NIVEAUX = [
        'débutant' => 0,     
        'intermédiaire' => 10, 
        'avancé' => 30,      
        'expert' => 50     
    ];

    /**
     * @param EntityManagerInterface $entityManager Gestionnaire d'entités Doctrine
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    /**
     * Ajoute des points pour une connexion et met à jour le niveau de l'utilisateur
     * 
     * @param Utilisateur $utilisateur L'utilisateur qui se connecte
     * @return void
     */    
    public function addConnectionPoints(Utilisateur $utilisateur): void
    {
        $utilisateur->setPointsConnexion($utilisateur->getPointsConnexion() + self::POINTS_CONNEXION);
        $this->updateUserLevel($utilisateur);
        $this->entityManager->flush();
    }
    
    /**
     * Ajoute des points pour une consultation et met à jour le niveau de l'utilisateur
     * 
     * @param Utilisateur $utilisateur L'utilisateur qui effectue la consultation
     * @return void
     */    
    public function addConsultationPoints(Utilisateur $utilisateur): void
    {
        $utilisateur->setPointsConsultation($utilisateur->getPointsConsultation() + self::POINTS_CONSULTATION);
        $this->updateUserLevel($utilisateur);
        $this->entityManager->flush();
    }
    
    /**
     * Met à jour le niveau d'expérience de l'utilisateur en fonction de ses points
     * 
     * Calcule le total des points de l'utilisateur et détermine le niveau
     * correspondant en fonction des seuils définis.
     * 
     * @param Utilisateur $utilisateur L'utilisateur dont le niveau doit être mis à jour
     * @return void
     */    
    public function updateUserLevel(Utilisateur $utilisateur): void
    {
        $totalPoints = $utilisateur->getPointsConnexion() + $utilisateur->getPointsConsultation();
    
        $niveau = 'débutant';
    
    foreach (self::NIVEAUX as $nom => $seuil) {
        if ($totalPoints >= $seuil) {
            $niveau = $nom;
        }
    }
    
    $utilisateur->setNiveauExperience($niveau);
    }

    /**
     * Calcule le nombre total de points de l'utilisateur
     * 
     * @param Utilisateur $utilisateur L'utilisateur dont on veut calculer les points
     * @return float Le nombre total de points
     */
    public function getTotalPoints(Utilisateur $utilisateur): float
    {
        return $utilisateur->getPointsConnexion() + $utilisateur->getPointsConsultation();
    }
  
    /**
     * Calcule le nombre de points manquants pour atteindre le niveau suivant
     * 
     * @param Utilisateur $utilisateur L'utilisateur concerné
     * @return float|null Le nombre de points nécessaires ou null si déjà au niveau maximum
     */  
    public function getPointsToNextLevel(Utilisateur $utilisateur): ?float
    {
        $totalPoints = $this->getTotalPoints($utilisateur);
        $currentLevel = $utilisateur->getNiveauExperience();
        
        $niveaux = array_keys(self::NIVEAUX);
        $currentIndex = array_search($currentLevel, $niveaux);
        
        if ($currentIndex === false || $currentIndex >= count($niveaux) - 1) {
            return null;
        }
        
        $nextLevel = $niveaux[$currentIndex + 1];
        $pointsRequired = self::NIVEAUX[$nextLevel];
        
        return $pointsRequired - $totalPoints;
    }
}
