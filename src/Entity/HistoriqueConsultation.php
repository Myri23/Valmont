<?php

namespace App\Entity;

use App\Repository\HistoriqueConsultationRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Utilisateur;

#[ORM\Entity(repositoryClass: HistoriqueConsultationRepository::class)]
class HistoriqueConsultation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $utilisateur = null;

    #[ORM\Column(length: 255)]
    private ?string $typeElement = null;

    #[ORM\Column(nullable: true)]
    private ?int $elementId = null;
    
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nomElement = null; // Nouveau champ pour le nom

    #[ORM\Column]
    private \DateTimeImmutable $dateConsultation;

    // Ajoutez les getters et setters pour le nouveau champ
    public function getNomElement(): ?string
    {
        return $this->nomElement;
    }

    public function setNomElement(?string $nomElement): self
    {
        $this->nomElement = $nomElement;
        return $this;
    }
    
// Getter et setter pour utilisateur
public function getUtilisateur(): ?Utilisateur
{
    return $this->utilisateur;
}

public function setUtilisateur(?Utilisateur $utilisateur): self
{
    $this->utilisateur = $utilisateur;
    return $this;
}

// Getter et setter pour id
public function getId(): ?int
{
    return $this->id;
}

// Getter et setter pour typeElement
public function getTypeElement(): ?string
{
    return $this->typeElement;
}

public function setTypeElement(string $typeElement): self
{
    $this->typeElement = $typeElement;
    return $this;
}

// Getter et setter pour elementId
public function getElementId(): ?int
{
    return $this->elementId;
}

public function setElementId(?int $elementId): self
{
    $this->elementId = $elementId;
    return $this;
}

// Getter et setter pour dateConsultation
public function getDateConsultation(): \DateTimeImmutable
{
    return $this->dateConsultation;
}

public function setDateConsultation(\DateTimeImmutable $dateConsultation): self
{
    $this->dateConsultation = $dateConsultation;
    return $this;
}
}
