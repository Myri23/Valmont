<?php

namespace App\Entity;

use App\Repository\CapteurQualiteAirRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CapteurQualiteAirRepository::class)]
class CapteurQualiteAir
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'capteursQualiteAir')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?ObjetConnecte $objet = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $niveauPm25 = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $niveauPm10 = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $niveauCo2 = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $niveauO3 = null;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private ?string $qualiteGlobale = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $derniereMesure = null;

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

    public function getNiveauPm25(): ?float
    {
        return $this->niveauPm25;
    }

    public function setNiveauPm25(?float $niveauPm25): self
    {
        $this->niveauPm25 = $niveauPm25;

        return $this;
    }

    public function getNiveauPm10(): ?float
    {
        return $this->niveauPm10;
    }

    public function setNiveauPm10(?float $niveauPm10): self
    {
        $this->niveauPm10 = $niveauPm10;

        return $this;
    }

    public function getNiveauCo2(): ?float
    {
        return $this->niveauCo2;
    }

    public function setNiveauCo2(?float $niveauCo2): self
    {
        $this->niveauCo2 = $niveauCo2;

        return $this;
    }

    public function getNiveauO3(): ?float
    {
        return $this->niveauO3;
    }

    public function setNiveauO3(?float $niveauO3): self
    {
        $this->niveauO3 = $niveauO3;

        return $this;
    }

    public function getQualiteGlobale(): ?string
    {
        return $this->qualiteGlobale;
    }

    public function setQualiteGlobale(?string $qualiteGlobale): self
    {
        $this->qualiteGlobale = $qualiteGlobale;

        return $this;
    }

    public function getDerniereMesure(): ?\DateTimeInterface
    {
        return $this->derniereMesure;
    }

    public function setDerniereMesure(?\DateTimeInterface $derniereMesure): self
    {
        $this->derniereMesure = $derniereMesure;

        return $this;
    }


}
