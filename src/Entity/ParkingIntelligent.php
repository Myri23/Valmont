<?php

namespace App\Entity;

use App\Repository\ParkingIntelligentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * ParkingIntelligent Entity
 * 
 * Cette entité représente un parking intelligent dans le système de ville intelligente.
 * Elle stocke les informations spécifiques aux parkings telles que le nombre de places,
 * les places disponibles et la localisation précise. Cette entité est liée à un ObjetConnecte
 * qui contient les propriétés communes à tous les objets connectés.
 */
#[ORM\Entity(repositoryClass: ParkingIntelligentRepository::class)]
class ParkingIntelligent
{
    /**
     * Identifiant unique du parking intelligent
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * Objet connecté associé à ce parking
     * 
     * Relation ManyToOne vers l'entité ObjetConnecte.
     * Cette relation est configurée pour supprimer en cascade les parkings
     * lorsque l'objet connecté associé est supprimé.
     */
    #[ORM\ManyToOne(inversedBy: 'parkingIntelligent')]
    #[ORM\JoinColumn(name: "objet_id", referencedColumnName: "id", nullable: false, onDelete: 'CASCADE')]
    private ?ObjetConnecte $objet = null;

    /**
     * Nombre total de places dans le parking
     */
    #[ORM\Column(type: 'integer')]
    private int $places_totales;

    /**
     * Nombre de places actuellement disponibles dans le parking
     * 
     * Cette valeur est mise à jour en temps réel par le système de capteurs
     */
    #[ORM\Column(type: 'integer')]
    private int $places_disponibles;

    /**
     * Localisation précise du parking
     * 
     * Détails de localisation plus spécifiques que la localisation générale
     * stockée dans l'entité ObjetConnecte
     */
    #[ORM\Column(type: 'string', length: 255)]
    private string $localisation_precise;

    /**
     * Récupère l'identifiant unique du parking intelligent
     * 
     * @return int|null L'identifiant ou null si non persisté
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Récupère l'objet connecté associé à ce parking
     * 
     * @return ObjetConnecte|null L'objet connecté associé
     */
    public function getObjet(): ?ObjetConnecte
    {
        return $this->objet;
    }

    /**
     * Définit l'objet connecté associé à ce parking
     * 
     * @param ObjetConnecte|null $objet L'objet connecté à associer
     * @return self
     */
    public function setObjet(?ObjetConnecte $objet): self
    {
        $this->objet = $objet;

        return $this;
    }

    /**
     * Récupère le nombre total de places dans le parking
     * 
     * @return int Le nombre total de places
     */
    public function getPlacesTotales(): int
    {
        return $this->places_totales;
    }

    /**
     * Définit le nombre total de places dans le parking
     * 
     * @param int $places_totales Le nouveau nombre total de places
     * @return self
     */
    public function setPlacesTotales(int $places_totales): self
    {
        $this->places_totales = $places_totales;

        return $this;
    }

    /**
     * Récupère le nombre de places actuellement disponibles
     * 
     * @return int Le nombre de places disponibles
     */
    public function getPlacesDisponibles(): int
    {
        return $this->places_disponibles;
    }

    /**
     * Définit le nombre de places actuellement disponibles
     * 
     * @param int $places_disponibles Le nouveau nombre de places disponibles
     * @return self
     */
    public function setPlacesDisponibles(int $places_disponibles): self
    {
        $this->places_disponibles = $places_disponibles;

        return $this;
    }

    /**
     * Récupère la localisation précise du parking
     * 
     * @return string La localisation précise
     */
    public function getLocalisationPrecise(): string
    {
        return $this->localisation_precise;
    }

    /**
     * Définit la localisation précise du parking
     * 
     * @param string $localisation_precise La nouvelle localisation précise
     * @return self
     */
    public function setLocalisationPrecise(string $localisation_precise): self
    {
        $this->localisation_precise = $localisation_precise;

        return $this;
    }
}
