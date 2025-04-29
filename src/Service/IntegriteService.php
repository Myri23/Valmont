<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Service de vérification et réparation de l'intégrité du système
 */
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

   /**
     * @param EntityManagerInterface $entityManager Gestionnaire d'entités Doctrine
     * @param ParameterBagInterface $params Accès aux paramètres de l'application
     * @param Filesystem $filesystem Service de gestion de fichiers
     */
    public function verifierIntegrite(): array
    {
        $resultats = [];
    
        $resultats['utilisateurs'] = $this->verifierUtilisateurs();
    
        $resultats['medias'] = $this->verifierMedias();
    
        return $resultats;
    }

    /**
     * Vérifie l'intégrité des données utilisateurs
     * 
     * @return array Problèmes détectés concernant les utilisateurs
     */    
    private function verifierUtilisateurs(): array
    {
        $problemes = [];
        
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
        
        return $problemes;
    }
    
    /**
     * Vérifie l'intégrité des fichiers médias
     * 
     * Vérifie la présence des fichiers médias essentiels au fonctionnement
     * de l'application, comme la vidéo d'accueil.
     * 
     * @return array Problèmes détectés concernant les fichiers médias
     */    
    private function verifierMedias(): array
    {
        $problemes = [];
        $publicDir = $this->params->get('kernel.project_dir') . '/public';
        
        if (!$this->filesystem->exists($publicDir . '/video/valmont.mp4')) {
            $problemes['video_accueil'] = [
                'description' => 'La vidéo d\'accueil est manquante',
                'path' => '/video/valmont.mp4'
            ];
        }
        
        return $problemes;
    }
    
    /**
     * Tente de réparer un problème d'intégrité spécifique
     * 
     * @param string $type Type de problème à réparer (ex: 'emails_invalides', 'video_accueil')
     * @return void
     */    
    public function reparer(string $type): void
    {
        switch ($type) {
            case 'emails_invalides':
                break;
            case 'video_accueil':
                break;
        }
    }
}
