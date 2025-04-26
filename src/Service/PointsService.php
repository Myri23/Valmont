<?php
// src/Service/PointsService.php
namespace App\Service;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;

class PointsService
{
    private EntityManagerInterface $entityManager;
    private const POINTS_CONNEXION = 0.25;
    private const POINTS_CONSULTATION = 0.50;
    
    // Définissez les seuils de niveaux
    private const NIVEAUX = [
        'débutant' => 0,      // 0-9 points
        'intermédiaire' => 10, // 10-29 points
        'avancé' => 30,        // 30-49 points
        'expert' => 50         // 50+ points
    ];
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    public function addConnectionPoints(Utilisateur $utilisateur): void
    {
        $utilisateur->setPointsConnexion($utilisateur->getPointsConnexion() + self::POINTS_CONNEXION);
        $this->updateUserLevel($utilisateur);
        $this->entityManager->flush();
    }
    
    public function addConsultationPoints(Utilisateur $utilisateur): void
    {
        $utilisateur->setPointsConsultation($utilisateur->getPointsConsultation() + self::POINTS_CONSULTATION);
        $this->updateUserLevel($utilisateur);
        $this->entityManager->flush();
    }
    
    private function updateUserLevel(Utilisateur $utilisateur): void
    {
        $totalPoints = $utilisateur->getPointsConnexion() + $utilisateur->getPointsConsultation();
        
        $niveau = 'débutant'; // Niveau par défaut
        
        foreach (self::NIVEAUX as $nom => $seuil) {
            if ($totalPoints >= $seuil) {
                $niveau = $nom;
            } else {
                break;
            }
        }
        
        $utilisateur->setNiveauExperience($niveau);
    }
    
    public function getTotalPoints(Utilisateur $utilisateur): float
    {
        return $utilisateur->getPointsConnexion() + $utilisateur->getPointsConsultation();
    }
    
    public function getPointsToNextLevel(Utilisateur $utilisateur): ?float
    {
        $totalPoints = $this->getTotalPoints($utilisateur);
        $currentLevel = $utilisateur->getNiveauExperience();
        
        // Trouver le prochain niveau
        $niveaux = array_keys(self::NIVEAUX);
        $currentIndex = array_search($currentLevel, $niveaux);
        
        if ($currentIndex === false || $currentIndex >= count($niveaux) - 1) {
            return null; // Déjà au niveau maximum
        }
        
        $nextLevel = $niveaux[$currentIndex + 1];
        $pointsRequired = self::NIVEAUX[$nextLevel];
        
        return $pointsRequired - $totalPoints;
    }
}
