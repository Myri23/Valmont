<?php

namespace App\Entity;

use App\Repository\ObjetConnecteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

use App\Entity\PoubelleConnectee;
use App\Entity\ParkingIntelligent;

#[ORM\Entity(repositoryClass: ObjetConnecteRepository::class)]
#[ORM\HasLifecycleCallbacks] 
class ObjetConnecte
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column]
    private ?int $id = null;
    
    #[ORM\Column(length: 255, unique: true)]
    private ?string $idUnique = null;
    
    #[ORM\PrePersist]
    public function generateIdUnique(): void
    {
        if ($this->idUnique === null) {
            $prefix = $this->type ? strtoupper(substr($this->type, 0, 3)) : 'OBJ';
            $this->idUnique = $prefix . '-' . date('Ymd') . '-' . uniqid();
        }
    }
    
    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $marque = null;

    #[ORM\Column(length: 100)]
    private ?string $etat = null;

    #[ORM\Column(length: 255)]
    private ?string $localisation = null;

    #[ORM\Column(type: 'integer')]
    private ?int $batteriePct = null;

    #[ORM\Column(type: 'boolean')]
    private ?bool $actif = null;

    #[ORM\OneToMany(mappedBy: 'objet', targetEntity: PoubelleConnectee::class, cascade: ['persist', 'remove'])]
    private Collection $poubelleConnectee;
    
    #[ORM\OneToMany(mappedBy: 'objet', targetEntity: ParkingIntelligent::class, cascade: ['persist', 'remove'])]
    private Collection $parkingIntelligent;
    
    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $derniereInteraction = null;

    public function __construct()
    {
        $this->poubelleConnectee = new ArrayCollection();
        $this->parkingIntelligent = new ArrayCollection();
    }
    
    public function getDerniereInteraction(): ?\DateTimeInterface
    {
        return $this->derniereInteraction;
    }

    public function setDerniereInteraction(?\DateTimeInterface $derniereInteraction): self
    {
        $this->derniereInteraction = $derniereInteraction;
        return $this;
    }

    public function getIdUnique(): ?string
    {
        return $this->idUnique;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;
        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;
        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): self
    {
        $this->localisation = $localisation;
        return $this;
    }

    public function getBatteriePct(): ?int
    {
        return $this->batteriePct;
    }

    public function setBatteriePct(int $batteriePct): self
    {
        $this->batteriePct = $batteriePct;
        return $this;
    }

    public function isActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;
        return $this;
    }

    /**
     * @return Collection<int, PoubelleConnectee>
     */
    public function getPoubelleConnectee(): Collection
    {
        return $this->poubelleConnectee;
    }
    
    public function addPoubelleConnectee(PoubelleConnectee $poubelleConnectee): self
    {
        if (!$this->poubelleConnectee->contains($poubelleConnectee)) {
            $this->poubelleConnectee->add($poubelleConnectee);
            $poubelleConnectee->setObjet($this);
        }
    
        return $this;
    }
    
    public function removePoubelleConnectee(PoubelleConnectee $poubelleConnectee): self
    {
        if ($this->poubelleConnectee->removeElement($poubelleConnectee)) {
            // set the owning side to null (unless already changed)
            if ($poubelleConnectee->getObjet() === $this) {
                $poubelleConnectee->setObjet(null);
            }
        }
        
        return $this;
    }
    
    /**
     * @return Collection<int, ParkingIntelligent>
     */
    public function getParkingIntelligent(): Collection
    {
        return $this->parkingIntelligent;
    }
    
    public function addParkingIntelligent(ParkingIntelligent $parkingIntelligent): self
    {
        if (!$this->parkingIntelligent->contains($parkingIntelligent)) {
            $this->parkingIntelligent->add($parkingIntelligent);
            $parkingIntelligent->setObjet($this);
        }
    
        return $this;
    }
    
    public function removeParkingIntelligent(ParkingIntelligent $parkingIntelligent): self
    {
        if ($this->parkingIntelligent->removeElement($parkingIntelligent)) {
            // set the owning side to null (unless already changed)
            if ($parkingIntelligent->getObjet() === $this) {
                $parkingIntelligent->setObjet(null);
            }
        }
        
        return $this;
    }
}
