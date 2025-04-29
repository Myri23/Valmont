<?php

namespace App\Entity;

use App\Repository\TransportRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Transport Entity
 * 
 * Cette entité représente un moyen de transport dans le système.
 * Elle stocke les informations de base sur les différents types
 * de transport disponibles dans la ville.
 */
#[ORM\Entity(repositoryClass: TransportRepository::class)]
class Transport
{
    /**
     * Identifiant unique du moyen de transport
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Type de moyen de transport
     */
    #[ORM\Column(length: 255)]
    private ?string $type = null;

    /**
     * Description détaillée du moyen de transport
     * 
     * Informations complémentaires sur le moyen de transport,
     * comme les itinéraires, les horaires, les tarifs, etc.
     */
    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    /**
     * Récupère l'identifiant unique du moyen de transport
     * 
     * @return int|null L'identifiant ou null si non persisté
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Récupère le type de moyen de transport
     * 
     * @return string|null Le type de transport
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Définit le type de moyen de transport
     * 
     * @param string $type Le nouveau type de transport
     * @return static
     */
    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Récupère la description du moyen de transport
     * 
     * @return string|null La description complète
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Définit la description du moyen de transport
     * 
     * @param string $description La nouvelle description
     * @return static
     */
    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
