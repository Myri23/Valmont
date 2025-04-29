<?php
namespace App\Command;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Commande pour mettre à jour les mots de passe des utilisateurs
 * avec le hachage correct.
 */
#[AsCommand(
    name: 'app:update-passwords',
    description: 'Met à jour les mots de passe utilisateurs avec le hachage correct',
)]
class UpdatePasswordsCommand extends Command
{
    /**
     * @param EntityManagerInterface $entityManager Gestionnaire d'entités Doctrine
     * @param UserPasswordHasherInterface $passwordHasher Service de hachage des mots de passe
     */
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }
    
    /**
     * Exécute la commande de mise à jour des mots de passe.
     * @param InputInterface $input Interface d'entrée de la console
     * @param OutputInterface $output Interface de sortie de la console
     * @return int Le code de retour de la commande (SUCCESS = 0, FAILURE = 1)
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Création d'une instance de SymfonyStyle pour une sortie plus agréable
        $io = new SymfonyStyle($input, $output);
        
        // Récupérer tous les utilisateurs
        $utilisateurs = $this->entityManager->getRepository(Utilisateur::class)->findAll();
        $count = 0;
        
        // Parcourir chaque utilisateur et mettre à jour le mot de passe
        foreach ($utilisateurs as $utilisateur) {
            // Récupérer le mot de passe en clair (si c'est une migration)
            $plainPassword = $utilisateur->getMotDePasse();
            
            // Hasher le mot de passe
            $hashedPassword = $this->passwordHasher->hashPassword($utilisateur, $plainPassword);
            
            // Mettre à jour le mot de passe
            $utilisateur->setMotDePasse($hashedPassword);
            $count++;
        }
        
        // Persistance des changements
        $this->entityManager->flush();
        
        // Affichage d'un message de succès
        $io->success(sprintf('Les mots de passe de %d utilisateurs ont été mis à jour avec succès !', $count));

        return Command::SUCCESS;
    }
}