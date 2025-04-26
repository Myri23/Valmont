<?php

namespace App\Entity;

use App\Repository\HistoriqueConnexionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HistoriqueConnexionRepository::class)]
class HistoriqueConnexion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $utilisateur;

    #[ORM\Column(type: 'datetime')]
    private $dateConnexion;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $ipConnexion;

    // Getter pour l'ID
    public function getId(): ?int
    {
        return $this->id;
    }

    // Getter pour l'utilisateur
    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    // Setter pour l'utilisateur
    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }

    // Getter pour la date de connexion
    public function getDateConnexion(): ?\DateTimeInterface
    {
        return $this->dateConnexion;
    }

    // Setter pour la date de connexion
    public function setDateConnexion(\DateTimeInterface $dateConnexion): self
    {
        $this->dateConnexion = $dateConnexion;
        return $this;
    }

    // Getter pour l'IP de connexion
    public function getIpConnexion(): ?string
    {
        return $this->ipConnexion;
    }

    // Setter pour l'IP de connexion
    public function setIpConnexion(?string $ipConnexion): self
    {
        $this->ipConnexion = $ipConnexion;
        return $this;
    }
}

