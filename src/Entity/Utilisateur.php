<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 50, unique: true)]
    private ?string $login = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $mot_de_passe = null;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $date_naissance = null;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private ?string $sexe = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $age = null;

    #[ORM\Column(type: 'string', length: 100, unique: true)]
    private ?string $email = null;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private ?string $type_membre = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $photo_url = null;

    #[ORM\Column(type: 'string', length: 20)]
    private string $type_utilisateur = 'visiteur';

    #[ORM\Column(type: 'string', length: 20, options: ['default' => 'débutant'])]
    private string $niveau_experience = 'débutant';

    #[ORM\Column(type: 'float', options: ['default' => 0])]
    private float $points_connexion = 0;

    #[ORM\Column(type: 'float', options: ['default' => 0])]
    private float $points_consultation = 0;

    #[ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $date_inscription = null;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $compte_valide = false;

    // Getters et setters générés automatiquement

    public function getId(): ?int { return $this->id; }

    public function getLogin(): ?string { return $this->login; }
    public function setLogin(string $login): self { $this->login = $login; return $this; }

    public function getMotDePasse(): ?string { return $this->mot_de_passe; }
    public function setMotDePasse(string $mot_de_passe): self { $this->mot_de_passe = $mot_de_passe; return $this; }

    public function getNom(): ?string { return $this->nom; }
    public function setNom(?string $nom): self { $this->nom = $nom; return $this; }

public function getPseudo(): ?string
{
    return $this->pseudo;
}

public function setPseudo(?string $pseudo): self
{
    $this->pseudo = $pseudo;
    return $this;
}

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
}
