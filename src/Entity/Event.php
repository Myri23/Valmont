<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Event Entity
 * 
 * Cette entité représente un événement dans le système.
 * Elle permet de stocker les informations relatives aux différents
 * types d'événements comme leur nom, description et date.
 */
#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    /**
     * Identifiant unique de l'événement
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]  
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Type d'événement
     */  
    #[ORM\Column(length: 255)]
    private ?string $type = null;

    /**
     * Nom ou titre de l'événement
     */
    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    /**
     * Description détaillée de l'événement
     */
    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

   /**
     * Date et heure de l'événement
     */
    #[ORM\Column(type: Types::TEXT)]
    private ?string $date = null;

    /**
     * Récupère l'identifiant unique de l'événement
     * 
     * @return int|null L'identifiant ou null si non persisté
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Récupère le type de l'événement
     * 
     * @return string|null Le type d'événement
     */
    public function getType(): ?string
    {
        return $this->type;
    }

  /**
     * Définit le type de l'événement
     * 
     * @param string $type Le nouveau type d'événement
     * @return static
     */
    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Récupère le nom de l'événement
     * 
     * @return string|null Le nom de l'événement
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * Définit le nom de l'événement
     * 
     * @param string $nom Le nouveau nom de l'événement
     * @return static
     */
    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Récupère la description de l'événement
     * 
     * @return string|null La description complète de l'événement
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Définit la description de l'événement
     * 
     * @param string $description La nouvelle description de l'événement
     * @return static
     */
    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Récupère la date de l'événement
     * 
     * @return string|null La date et heure de l'événement au format texte
     */
    public function getDate(): ?string
    {
        return $this->date;
    }

    /**
     * Définit la date de l'événement
     * 
     * @param string $date La nouvelle date et heure de l'événement
     * @return static
     */
    public function setDate(string $date): static
    {
        $this->date = $date;

        return $this;
    }
}
