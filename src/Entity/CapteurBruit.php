<?php

namespace App\Entity;

use App\Repository\CapteurBruitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CapteurBruitRepository::class)]
class CapteurBruit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: ObjetConnecte::class, inversedBy: 'capteursBruit')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?ObjetConnecte $objet = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $niveau_decibel = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $seuil_alerte = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $derniere_alerte = null;

    // Getters et setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getObjet(): ?ObjetConnecte
    {
        return $this->objet;
    }

    public function setObjet(?ObjetConnecte $objet): self
    {
        $this->objet = $objet;
        return $this;
    }

    public function getNiveauDecibel(): ?float
    {
        return $this->niveau_decibel;
    }

    public function setNiveauDecibel(?float $niveau_decibel): self
    {
        $this->niveau_decibel = $niveau_decibel;
        return $this;
    }

    public function getSeuilAlerte(): ?float
    {
        return $this->seuil_alerte;
    }

    public function setSeuilAlerte(?float $seuil_alerte): self
    {
        $this->seuil_alerte = $seuil_alerte;
        return $this;
    }

    public function getDerniereAlerte(): ?\DateTimeInterface
    {
        return $this->derniere_alerte;
    }

    public function setDerniereAlerte(?\DateTimeInterface $derniere_alerte): self
    {
        $this->derniere_alerte = $derniere_alerte;
        return $this;
    }
}