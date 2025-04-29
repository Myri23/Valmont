<?php

// src/Entity/AppearanceConfig.php
namespace App\Entity;

use App\Repository\AppearanceConfigRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * AppearanceConfig Entity
 * 
 * Cette classe gère la configuration d'apparence de l'application, permettant
 * la personnalisation du thème, des couleurs et de la disposition des modules.
 */
#[ORM\Entity(repositoryClass: AppearanceConfigRepository::class)]
class AppearanceConfig
{
    /**
     * Identifiant unique de la configuration
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Nom du thème sélectionné
     * Valeur par défaut: 'default'
     */
    #[ORM\Column(length: 50)]
    private ?string $themeName = 'default';

    /**
     * Couleur principale utilisée dans l'interface
     * Format: code hexadécimal
     */
    #[ORM\Column(length: 30)]
    private ?string $primaryColor = '#3498db';

    /**
     * Couleur secondaire utilisée dans l'interface
     * Format: code hexadécimal
     */
    #[ORM\Column(length: 30)]
    private ?string $secondaryColor = '#2ecc71';

    /**
     * Configuration de la disposition des modules
     */
    #[ORM\Column(type: 'json')]
    private array $moduleLayout = [
        'information' => ['enabled' => true, 'order' => 1],
        'visualisation' => ['enabled' => true, 'order' => 2],
        'gestion' => ['enabled' => true, 'order' => 3],
        'administration' => ['enabled' => true, 'order' => 4],
    ];

    /**
     * Styles CSS personnalisés additionnels
     * Stockés sous forme d'un tableau JSON
     */
    #[ORM\Column(type: 'json')]
    private array $additionalStyles = [];

    /**
     * Récupère l'identifiant de la configuration
     * 
     * @return int|null L'identifiant ou null si non persisté
     */
    public function getId(): ?int
    {
       return $this->id;
    }

    /**
     * Récupère le nom du thème
     * 
     * @return string|null Le nom du thème
     */
    public function getThemeName(): ?string
    {
        return $this->themeName;
    }

   /**
     * Définit le nom du thème
     * 
     * @param string $themeName Le nouveau nom du thème
     * @return self
     */
    public function setThemeName(string $themeName): self
    {
        $this->themeName = $themeName;
        return $this;
    }

    /**
     * Récupère la couleur principale
     * 
     * @return string|null La couleur principale au format hexadécimal
     */
    public function getPrimaryColor(): ?string
    {
        return $this->primaryColor;
    }

    /**
     * Définit la couleur principale
     * 
     * @param string $primaryColor La nouvelle couleur principale au format hexadécimal
     * @return self
     */
    public function setPrimaryColor(string $primaryColor): self
    {
        $this->primaryColor = $primaryColor;
        return $this;
    }

    /**
     * Récupère la couleur secondaire
     *
     * @return string|null La couleur secondaire au format hexadécimal
     */
    public function getSecondaryColor(): ?string
    {
        return $this->secondaryColor;
    }

    /**
     * Définit la couleur secondaire
     *
     * @param string $secondaryColor La nouvelle couleur secondaire au format hexadécimal
     * @return self
     */
    public function setSecondaryColor(string $secondaryColor): self
    {
        $this->secondaryColor = $secondaryColor;
        return $this;
    }

    /**
     * Récupère la configuration de disposition des modules
     *
     * @return array La disposition des modules
     */
    public function getModuleLayout(): array
    {
        return $this->moduleLayout;
    }

    /**
     * Définit la configuration de disposition des modules
     *
     * @param array $moduleLayout La nouvelle disposition des modules
     * @return self
     */
    public function setModuleLayout(array $moduleLayout): self
    {
        $this->moduleLayout = $moduleLayout;
        return $this;
    }

    /**
     * Récupère les styles additionnels
     *
     * @return array Les styles CSS additionnels
     */
    public function getAdditionalStyles(): array
    {
        return $this->additionalStyles;
    }

    /**
     * Définit les styles additionnels
     *
     * @param array $additionalStyles Les nouveaux styles CSS additionnels
     * @return self
     */
    public function setAdditionalStyles(array $additionalStyles): self
    {
        $this->additionalStyles = $additionalStyles;
        return $this;
    }
}
