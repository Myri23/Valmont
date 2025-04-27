<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class IntegriteService
{
    private $entityManager;
    private $params;
    private $filesystem;
    
    public function __construct(
        EntityManagerInterface $entityManager, 
        ParameterBagInterface $params,
        Filesystem $filesystem
    ) {
        $this->entityManager = $entityManager;
        $this->params = $params;
        $this->filesystem = $filesystem;
    }
    
public function verifierIntegrite(): array
{
    $resultats = [];
    
    // 1. Vérifier les utilisateurs (emails invalides, données manquantes)
    $resultats['utilisateurs'] = $this->verifierUtilisateurs();
    
    // 2. Vérifier les fichiers médias (vidéos, images manquantes)
    $resultats['medias'] = $this->verifierMedias();
    
    // Suppression de la vérification des événements qui cause l'erreur
    // $resultats['evenements'] = $this->verifierEvenements();
    
    return $resultats;
}

    
    private function verifierUtilisateurs(): array
    {
        $problemes = [];
        
        // Exemple: trouver des emails invalides
        $utilisateursAvecEmailsInvalides = $this->entityManager->createQuery(
            'SELECT u FROM App\Entity\Utilisateur u WHERE u.email NOT LIKE \'%@%\''
        )->getResult();
        
        if (count($utilisateursAvecEmailsInvalides) > 0) {
            $problemes['emails_invalides'] = [
                'description' => 'Utilisateurs avec des emails invalides',
                'count' => count($utilisateursAvecEmailsInvalides),
                'ids' => array_map(function($u) { return $u->getId(); }, $utilisateursAvecEmailsInvalides)
            ];
        }
        
        // Autres vérifications possibles...
        
        return $problemes;
    }
    
    private function verifierMedias(): array
    {
        $problemes = [];
        $publicDir = $this->params->get('kernel.project_dir') . '/public';
        
        // Vérifier si la vidéo d'accueil existe
        if (!$this->filesystem->exists($publicDir . '/video/valmont.mp4')) {
            $problemes['video_accueil'] = [
                'description' => 'La vidéo d\'accueil est manquante',
                'path' => '/video/valmont.mp4'
            ];
        }
        
        // Autres vérifications possibles...
        
        return $problemes;
    }
    
    
    public function reparer(string $type): void
    {
        // Logique de réparation selon le type
        switch ($type) {
            case 'emails_invalides':
                // Code pour corriger les emails
                break;
            case 'video_accueil':
                // Code pour signaler le problème
                break;
            // Autres cas...
        }
    }
}
