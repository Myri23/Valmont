<?php

namespace App\Entity;

use App\Repository\ParkingIntelligentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParkingIntelligentRepository::class)]
class ParkingIntelligent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: ObjetConnecte::class, inversedBy: 'parkings')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?ObjetConnecte $objet = null;

    #[ORM\Column(type: 'integer')]
    private ?int $places_totales = null;

    #[ORM\Column(type: 'integer')]
    private ?int $places_disponibles = null;

    #[ORM\Column(type: 'string', length: 100)]
    private ?string $localisation_precise = null;

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

    public function getPlacesTotales(): ?int
    {
        return $this->places_totales;
    }

    public function setPlacesTotales(int $places_totales): self
    {
        $this->places_totales = $places_totales;
        return $this;
    }

    public function getPlacesDisponibles(): ?int
    {
        return $this->places_disponibles;
    }

    public function setPlacesDisponibles(int $places_disponibles): self
    {
        $this->places_disponibles = $places_disponibles;
        return $this;
    }

    public function getLocalisationPrecise(): ?string
    {
        return $this->localisation_precise;
    }

    public function setLocalisationPrecise(string $localisation_precise): self
    {
        $this->localisation_precise = $localisation_precise;
        return $this;
    }
}