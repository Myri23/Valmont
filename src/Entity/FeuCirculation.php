<?php

namespace App\Entity;

use App\Repository\FeuCirculationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FeuCirculationRepository::class)]
class FeuCirculation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'feuxCirculation')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?ObjetConnecte $objet = null;

    //['vert', 'orange', 'rouge', 'clignotant', 'eteint']
    #[ORM\Column(type: 'string', length: 20, options: ['default' => 'rouge'])]
    private string $etatActuel = 'rouge';

    //['normal', 'adaptatif', 'clignotant', 'eteint']
    #[ORM\Column(type: 'string', length: 20, options: ['default' => 'normal'])]
    private string $modeFonctionnement = 'normal';

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $dureeCycle = null;

    #[ORM\Column(type: 'boolean')]
    private bool $prioriteTransportCommun = false;

    #[ORM\Column(type: 'boolean')]
    private bool $detectionVehicules = false;

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

    public function getEtatActuel(): string
    {
        return $this->etatActuel;
    }

    public function setEtatActuel(string $etatActuel): self
    {
        $this->etatActuel = $etatActuel;

        return $this;
    }

    public function getModeFonctionnement(): string
    {
        return $this->modeFonctionnement;
    }

    public function setModeFonctionnement(string $modeFonctionnement): self
    {
        $this->modeFonctionnement = $modeFonctionnement;

        return $this;
    }

    public function getDureeCycle(): ?int
    {
        return $this->dureeCycle;
    }

    public function setDureeCycle(?int $dureeCycle): self
    {
        $this->dureeCycle = $dureeCycle;

        return $this;
    }

    public function isPrioriteTransportCommun(): bool
    {
        return $this->prioriteTransportCommun;
    }

    public function setPrioriteTransportCommun(bool $prioriteTransportCommun): self
    {
        $this->prioriteTransportCommun = $prioriteTransportCommun;

        return $this;
    }

    public function isDetectionVehicules(): bool
    {
        return $this->detectionVehicules;
    }

    public function setDetectionVehicules(bool $detectionVehicules): self
    {
        $this->detectionVehicules = $detectionVehicules;

        return $this;
    }
}
