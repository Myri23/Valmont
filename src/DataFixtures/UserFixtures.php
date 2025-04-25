<?php
namespace App\DataFixtures;

use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;

class UserFixtures extends Fixture
{
    private $passwordHasher;
    
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        
        // Créer un administrateur
        $admin = new Utilisateur();
        $admin->setLogin('admin');
        $admin->setEmail('admin@example.com');
        $admin->setMotDePasse($this->passwordHasher->hashPassword($admin, 'admin123'));
        $admin->setNom('System');
        $admin->setPrenom('Admin');
        $admin->setTypeUtilisateur('admin');
        $admin->setNiveauExperience('expert');
        $admin->setPointsConnexion(50);
        $admin->setPointsConsultation(50);
        $admin->setDateInscription(new \DateTime());
        $admin->setCompteValide(true);
        $admin->setTypeMembre('administrateur');
        $manager->persist($admin);
        
        // Créer des utilisateurs simples
        for ($i = 0; $i < 20; $i++) {
            $user = new Utilisateur();
            $user->setLogin($faker->userName);
            $user->setEmail($faker->email);
            $user->setMotDePasse($this->passwordHasher->hashPassword($user, 'password'));
            $user->setNom($faker->lastName);
            $user->setPrenom($faker->firstName);
            $user->setDateNaissance($faker->dateTimeBetween('-60 years', '-18 years'));
            $user->setSexe($faker->randomElement(['Homme', 'Femme', 'Autre']));
            $user->setAge($faker->numberBetween(18, 65));
            $user->setTypeUtilisateur('simple');
            $user->setNiveauExperience('débutant');
            $user->setPointsConnexion($faker->randomFloat(2, 0, 1.5));
            $user->setPointsConsultation($faker->randomFloat(2, 0, 1.5));
            $user->setDateInscription(new \DateTime());
            $user->setCompteValide(true);
            $user->setTypeMembre($faker->randomElement(['parent', 'enfant', 'employé', 'visiteur']));
            $user->setPhotoUrl('default.jpg');
            $manager->persist($user);
        }
        
        $manager->flush();
    }
}