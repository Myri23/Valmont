<?php

namespace App\Entity;

use App\Repository\LampadaireIntelligentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LampadaireIntelligentRepository::class)]
class LampadaireIntelligent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'lampadairesIntelligents')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?ObjetConnecte $objet = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $intensiteLumineuse = null;

    #[ORM\Column(type: 'string', length: 20, options: ['default' => 'adaptatif'])]
    private string $modeEclairage = 'adaptatif';

    #[ORM\Column(type: 'boolean')]
    private bool $capteurMouvement = true;

    #[ORM\Column(type: 'boolean')]
    private bool $capteurLuminosite = true;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $heuresFonctionnement = null;

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

    public function getIntensiteLumineuse(): ?int
    {
        return $this->intensiteLumineuse;
    }

    public function setIntensiteLumineuse(?int $intensiteLumineuse): self
    {
        $this->intensiteLumineuse = $intensiteLumineuse;

        return $this;
    }

    public function getModeEclairage(): string
    {
        return $this->modeEclairage;
    }

    public function setModeEclairage(string $modeEclairage): self
    {
        $this->modeEclairage = $modeEclairage;

        return $this;
    }

    public function isCapteurMouvement(): bool
    {
        return $this->capteurMouvement;
    }

    public function setCapteurMouvement(bool $capteurMouvement): self
    {
        $this->capteurMouvement = $capteurMouvement;

        return $this;
    }

    public function isCapteurLuminosite(): bool
    {
        return $this->capteurLuminosite;
    }

    public function setCapteurLuminosite(bool $capteurLuminosite): self
    {
        $this->capteurLuminosite = $capteurLuminosite;

        return $this;
    }

    public function getHeuresFonctionnement(): ?string
    {
        return $this->heuresFonctionnement;
    }

    public function setHeuresFonctionnement(?string $heuresFonctionnement): self
    {
        $this->heuresFonctionnement = $heuresFonctionnement;

        return $this;
    }

}
