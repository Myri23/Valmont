<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;


/**
 * Utilisateur Entity
 * 
 * Cette entité représente un utilisateur du système.
 */
#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * Identifiant unique de l'utilisateur
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * Identifiant de connexion unique de l'utilisateur
     */
    #[ORM\Column(type: 'string', length: 50, unique: true)]
    private ?string $login = null;

    /**
     * Mot de passe hashé de l'utilisateur
     */
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $mot_de_passe = null;

    /**
     * Nom de famille de l'utilisateur
     */
    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $nom = null;

    /**
     * Prénom de l'utilisateur
     */
    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $prenom = null;

    /**
     * Date de naissance de l'utilisateur
     */
    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $date_naissance = null;

    /**
     * Sexe de l'utilisateur
     */
    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private ?string $sexe = null;

    /**
     * Âge de l'utilisateur
     */
    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $age = null;

    /**
     * Adresse email unique de l'utilisateur
     */
    #[ORM\Column(type: 'string', length: 100, unique: true)]
    private ?string $email = null;

    /**
     * Type d'adhésion ou statut de membre
     */
    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private ?string $type_membre = null;

    /**
     * URL vers la photo de profil de l'utilisateur
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $photo_url = null;

    /**
     * Type d'utilisateur définissant son rôle principal dans le système
     */
    #[ORM\Column(type: 'string', length: 20)]
    private string $type_utilisateur = 'visiteur';

    /**
     * Niveau d'expérience de l'utilisateur dans le système
     */
    #[ORM\Column(type: 'string', length: 20, options: ['default' => 'débutant'])]
    private string $niveau_experience = 'débutant';

    /**
     * Points accumulés pour les connexions au système
     */
    #[ORM\Column(type: 'float', options: ['default' => 0])]
    private float $points_connexion = 0;

    /**
     * Points accumulés pour les consultations d'éléments
     */
    #[ORM\Column(type: 'float', options: ['default' => 0])]
    private float $points_consultation = 0;

    /**
     * Date et heure d'inscription de l'utilisateur
     */
    #[ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $date_inscription = null;

    /**
     * Indique si le compte est actuellement valide et actif
     */
    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    private bool $compte_valide = true;

    /**
     * Jeton utilisé pour confirmer l'email ou réinitialiser le mot de passe
     */
    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $confirmationToken = null;

    /**
     * Indique si l'adresse email de l'utilisateur a été confirmée
     */
    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $isConfirmed = false;


    /**
     * Statut du processus de vérification de l'utilisateur
     */
    #[ORM\Column(type: 'string', length: 20, options: ['default' => 'en_attente'])]
    private string $statut_verification = 'en_attente';

    /**
     * Getters et setters
     * 
     * Ces méthodes permettent d'accéder aux propriétés de l'entité Utilisateur
     * et de les modifier. Elles suivent le pattern standard de Symfony
     * avec une méthode get/is pour l'accès et une méthode set pour la modification
     * qui retourne $this pour permettre le chaînage des appels.
     * 
     * Les méthodes spécifiques ou comportant une logique particulière sont 
     * documentées individuellement.
     */
    public function getId(): ?int { return $this->id; }

    public function getLogin(): ?string { return $this->login; }
    public function setLogin(string $login): self { $this->login = $login; return $this; }

    public function getMotDePasse(): ?string { return $this->mot_de_passe; }
    public function setMotDePasse(string $mot_de_passe): self { $this->mot_de_passe = $mot_de_passe; return $this; }

    public function getNom(): ?string { return $this->nom; }
    public function setNom(?string $nom): self { $this->nom = $nom; return $this; }

    public function getPrenom(): ?string { return $this->prenom; }
    public function setPrenom(?string $prenom): self { $this->prenom = $prenom; return $this; }

    public function getDateNaissance(): ?\DateTimeInterface { return $this->date_naissance; }
    public function setDateNaissance(?\DateTimeInterface $date_naissance): self { $this->date_naissance = $date_naissance; return $this; }

    public function getSexe(): ?string { return $this->sexe; }
    public function setSexe(?string $sexe): self { $this->sexe = $sexe; return $this; }

    public function getAge(): ?int { return $this->age; }
    public function setAge(?int $age): self { $this->age = $age; return $this; }

    public function getEmail(): ?string { return $this->email; }
    public function setEmail(string $email): self { $this->email = $email; return $this; }

    public function getTypeMembre(): ?string { return $this->type_membre; }
    public function setTypeMembre(?string $type_membre): self { $this->type_membre = $type_membre; return $this; }

    public function getPhotoUrl(): ?string { return $this->photo_url; }
    public function setPhotoUrl(?string $photo_url): self { $this->photo_url = $photo_url; return $this; }

    public function getTypeUtilisateur(): string { return $this->type_utilisateur; }
    public function setTypeUtilisateur(string $type_utilisateur): self { $this->type_utilisateur = $type_utilisateur; return $this; }

    public function getNiveauExperience(): string { return $this->niveau_experience; }
    public function setNiveauExperience(string $niveau_experience): self { $this->niveau_experience = $niveau_experience; return $this; }

    public function getPointsConnexion(): float { return $this->points_connexion; }
    public function setPointsConnexion(float $points_connexion): self { $this->points_connexion = $points_connexion; return $this; }

    public function getPointsConsultation(): float { return $this->points_consultation; }
    public function setPointsConsultation(float $points_consultation): self { $this->points_consultation = $points_consultation; return $this; }

    public function getDateInscription(): ?\DateTimeInterface { return $this->date_inscription; }
    public function setDateInscription(?\DateTimeInterface $date_inscription): self { $this->date_inscription = $date_inscription; return $this; }

    public function isCompteValide(): bool { return $this->compte_valide; }
    public function setCompteValide(bool $compte_valide): self { $this->compte_valide = $compte_valide; return $this; }

    public function getStatutVerification(): string { return $this->statut_verification; }
    public function setStatutVerification(string $statut_verification): self { $this->statut_verification = $statut_verification; return $this; }

    /**
     * Récupère les rôles de l'utilisateur
     * 
     * Cette méthode est requise par l'interface UserInterface.
     * Elle attribue dynamiquement les rôles en fonction du type d'utilisateur
     * et du niveau d'expérience.
     * 
     * @return array<string> Les rôles attribués à l'utilisateur
     */
    public function getRoles(): array
    {
        $roles = ['ROLE_USER']; // Tous les utilisateurs ont ROLE_USER
        
        if ($this->type_utilisateur === 'administrateur'){
        	$roles[] = 'ROLE_ADMIN';
        }
        // Ajouter des rôles en fonction du niveau d'expérience
        switch($this->niveau_experience) {
            case 'intermédiaire':
                $roles[] = 'ROLE_INTERMEDIAIRE';
                break;
            case 'avancé':
                $roles[] = 'ROLE_AVANCE';
                break;
            case 'expert':
                $roles[] = 'ROLE_EXPERT';
                break;
        }
        
        return array_unique($roles);
    }

    /**
     * Récupère le mot de passe hashé pour l'authentification
     * 
     * Cette méthode est requise par l'interface PasswordAuthenticatedUserInterface.
     * 
     * @return string Le mot de passe hashé
     */
    public function getPassword(): string
    {
        return $this->mot_de_passe;
    }

    /**
     * Récupère le sel utilisé pour le hashage du mot de passe
     * 
     * Cette méthode est requise par UserInterface mais est dépréciée depuis Symfony 5.3.
     * Elle n'est pas nécessaire avec les algorithmes modernes de hashage.
     * 
     * @deprecated depuis Symfony 5.3
     * @return string|null Le sel (toujours null dans cette implémentation)
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * Efface les informations sensibles de l'utilisateur
     * 
     * Cette méthode est requise par UserInterface.
     * Elle est appelée après l'authentification pour supprimer les données
     * sensibles qui ne doivent pas être stockées en session.
     */
    public function eraseCredentials(): void
    {
        // Utilisé si on stocke temporairement des données sensibles
        // $this->plainPassword = null;
    }

    /**
     * Récupère l'identifiant unique de l'utilisateur pour l'authentification
     * 
     * Cette méthode est requise par UserInterface.
     * 
     * @return string L'identifiant unique
     */
    public function getUserIdentifier(): string
    {
        return $this->login;
    }

    /**
     * Récupère le nom d'utilisateur pour l'authentification
     * 
     * @deprecated depuis Symfony 5.3, utilisez getUserIdentifier() à la place
     * @return string Le nom d'utilisateur
     */
    public function getUsername(): string
    {
        return $this->login;
    }

    /**
     * Récupère le jeton de confirmation utilisé pour la validation d'email
     * ou la réinitialisation de mot de passe
     * 
     * @return string|null Le jeton de confirmation ou null si non défini
     */
    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    /**
     * Définit le jeton de confirmation
     * 
     * @param string|null $confirmationToken Le nouveau jeton ou null pour le supprimer
     * @return self
     */
    public function setConfirmationToken(?string $confirmationToken): self
    {
        $this->confirmationToken = $confirmationToken;
        return $this;
    }

    /**
     * Vérifie si le compte a été confirmé
     * 
     * @return bool true si le compte est confirmé, false sinon
     */
    public function isConfirmed(): bool
    {
        return $this->isConfirmed;
    }

    /**
     * Définit le statut de confirmation du compte
     * 
     * @param bool $isConfirmed Le nouveau statut de confirmation
     * @return self
     */
    public function setIsConfirmed(bool $isConfirmed): self
    {
        $this->isConfirmed = $isConfirmed;
        return $this;
    }

}
