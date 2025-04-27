<?php

// src/Entity/AppearanceConfig.php
namespace App\Entity;

use App\Repository\AppearanceConfigRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AppearanceConfigRepository::class)]
class AppearanceConfig
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $themeName = 'default';

    #[ORM\Column(length: 30)]
    private ?string $primaryColor = '#3498db';

    #[ORM\Column(length: 30)]
    private ?string $secondaryColor = '#2ecc71';

    #[ORM\Column(type: 'json')]
    private array $moduleLayout = [
        'information' => ['enabled' => true, 'order' => 1],
        'visualisation' => ['enabled' => true, 'order' => 2],
        'gestion' => ['enabled' => true, 'order' => 3],
        'administration' => ['enabled' => true, 'order' => 4],
    ];

    #[ORM\Column(type: 'json')]
    private array $additionalStyles = [];

    // Dans AppearanceConfig.php, ajoutez ces méthodes:

public function getId(): ?int
{
    return $this->id;
}

public function getThemeName(): ?string
{
    return $this->themeName;
}

public function setThemeName(string $themeName): self
{
    $this->themeName = $themeName;
    return $this;
}

public function getPrimaryColor(): ?string
{
    return $this->primaryColor;
}

public function setPrimaryColor(string $primaryColor): self
{
    $this->primaryColor = $primaryColor;
    return $this;
}

public function getSecondaryColor(): ?string
{
    return $this->secondaryColor;
}

public function setSecondaryColor(string $secondaryColor): self
{
    $this->secondaryColor = $secondaryColor;
    return $this;
}

public function getModuleLayout(): array
{
    return $this->moduleLayout;
}

public function setModuleLayout(array $moduleLayout): self
{
    $this->moduleLayout = $moduleLayout;
    return $this;
}

public function getAdditionalStyles(): array
{
    return $this->additionalStyles;
}

public function setAdditionalStyles(array $additionalStyles): self
{
    $this->additionalStyles = $additionalStyles;
    return $this;
}
}
