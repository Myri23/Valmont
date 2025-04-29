<?php

namespace App\Entity;

use App\Repository\LieuRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Lieu Entity
 * 
 * Cette entité représente un lieu d'intérêt dans la ville.
 * Elle permet de stocker des informations sur différents types de lieux
 * comme des restaurants, bibliothèques, musées, etc. avec leurs caractéristiques spécifiques.
 */
#[ORM\Entity(repositoryClass: LieuRepository::class)]
class Lieu
{
    /**
     * Identifiant unique du lieu
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /*
     * Type de lieu
     * Ce champ détermine quels attributs spécifiques sont pertinents pour ce lieu.
     */
    #[ORM\Column(length: 255)]
    private ?string $type = null;

    /**
     * Nom ou titre du lieu
     */
    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    /**
     * Description détaillée du lieu
     * 
     * Informations générales sur le lieu, son histoire, ses caractéristiques, etc.
     */
    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    /**
     * Horaires d'ouverture du lieu
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $horaire = null;

    /**
     * Informations d'accès au lieu
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $acces = null;

    /**
     * Menu ou services proposés
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $menu = null;

    /**
     * Livres
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $livre = null;

    /**
     * Auteurs
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $auteur = null;

    /**
     * Récupère l'identifiant unique du lieu
     * 
     * @return int|null L'identifiant ou null si non persisté
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Récupère le type de lieu
     * 
     * @return string|null Le type de lieu
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Définit le type de lieu
     * 
     * @param string $type Le nouveau type de lieu
     * @return static
     */
    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Récupère le nom du lieu
     * 
     * @return string|null Le nom du lieu
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * Définit le nom du lieu
     * 
     * @param string $nom Le nouveau nom du lieu
     * @return static
     */
    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Récupère la description du lieu
     * 
     * @return string|null La description complète du lieu
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Définit la description du lieu
     * 
     * @param string $description La nouvelle description du lieu
     * @return static
     */
    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Récupère les horaires d'ouverture du lieu
     * 
     * @return string|null Les horaires d'ouverture ou null si non définis
     */
    public function getHoraire(): ?string
    {
        return $this->horaire;
    }

    /**
     * Définit les horaires d'ouverture du lieu
     * 
     * @param string|null $horaire Les nouveaux horaires d'ouverture ou null pour supprimer
     * @return static
     */
    public function setHoraire(?string $horaire): static
    {
        $this->horaire = $horaire;

        return $this;
    }

    /**
     * Récupère les informations d'accès au lieu
     * 
     * @return string|null Les informations d'accès ou null si non définies
     */
    public function getAcces(): ?string
    {
        return $this->acces;
    }

    /**
     * Définit les informations d'accès au lieu
     * 
     * @param string|null $acces Les nouvelles informations d'accès ou null pour supprimer
     * @return static
     */
    public function setAcces(?string $acces): static
    {
        $this->acces = $acces;

        return $this;
    }

/**
     * Récupère le menu ou les services proposés par le lieu
     * 
     * @return string|null Le menu/services ou null si non définis
     */
    public function getMenu(): ?string
    {
        return $this->menu;
    }

    /**
     * Définit le menu ou les services proposés par le lieu
     * 
     * @param string|null $menu Le nouveau menu/services ou null pour supprimer
     * @return static
     */
    public function setMenu(?string $menu): static
    {
        $this->menu = $menu;

        return $this;
    }

    /**
     * Récupère le livre en vedette
     * 
     * @return string|null Le titre du livre ou null si non défini
     */
    public function getLivre(): ?string
    {
        return $this->livre;
    }

    /**
     * Définit le livre en vedette
     * 
     * @param string|null $livre Le nouveau titre de livre ou null pour supprimer
     * @return static
     */
    public function setLivre(?string $livre): static
    {
        $this->livre = $livre;

        return $this;
    }

    /**
     * Récupère l'auteur en vedette
     * 
     * @return string|null Le nom de l'auteur ou null si non défini
     */
    public function getAuteur(): ?string
    {
        return $this->auteur;
    }

    /**
     * Définit l'auteur en vedette
     * 
     * @param string|null $auteur Le nouveau nom d'auteur ou null pour supprimer
     * @return static
     */
    public function setAuteur(?string $auteur): static
    {
        $this->auteur = $auteur;

        return $this;
    }
}
