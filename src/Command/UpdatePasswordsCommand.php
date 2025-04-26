<?php
// src/Command/UpdatePasswordsCommand.php

namespace App\Command;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:update-passwords',
    description: 'Met à jour les mots de passe utilisateurs avec le hachage correct',
)]
class UpdatePasswordsCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $utilisateurs = $this->entityManager->getRepository(Utilisateur::class)->findAll();
        $count = 0;
        
        foreach ($utilisateurs as $utilisateur) {
            // Récupérer le mot de passe en clair (si c'est une migration)
            $plainPassword = $utilisateur->getMotDePasse();
            
            // Hasher le mot de passe
            $hashedPassword = $this->passwordHasher->hashPassword($utilisateur, $plainPassword);
            
            // Mettre à jour le mot de passe
            $utilisateur->setMotDePasse($hashedPassword);
            $count++;
        }
        
        $this->entityManager->flush();
        
        $io->success(sprintf('Les mots de passe de %d utilisateurs ont été mis à jour avec succès !', $count));

        return Command::SUCCESS;
    }
}