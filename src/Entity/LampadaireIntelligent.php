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

    #[ORM\ManyToOne(inversedBy: 'lampadaireIntelligent')]
    #[ORM\JoinColumn(name: "objet_id", referencedColumnName: "id", nullable: false, onDelete: 'CASCADE')]
    private ?ObjetConnecte $objet = null;

    #[ORM\Column(type: 'time', nullable: true)]
    private ?\DateTimeInterface $heureAllumage = null;
    
    #[ORM\Column(type: 'integer')]
    private int $dureeAllumage = 0; // durée en minutes

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

    public function getHeureAllumage(): ?\DateTimeInterface
    {
        return $this->heureAllumage;
    }

    public function setHeureAllumage(?\DateTimeInterface $heureAllumage): self
    {
        $this->heureAllumage = $heureAllumage;

        return $this;
    }
    
    public function getDureeAllumage(): int
    {
        return $this->dureeAllumage;
    }

    public function setDureeAllumage(int $dureeAllumage): self
    {
        $this->dureeAllumage = $dureeAllumage;

        return $this;
    }
}
