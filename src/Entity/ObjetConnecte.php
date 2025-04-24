<?php

namespace App\Entity;

use App\Repository\ObjetConnecteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\CameraSurveillance;
use App\Entity\BorneRecharge;
use App\Entity\CapteurQualiteAir;
use App\Entity\FeuCirculation;
use App\Entity\LampadaireIntelligent;
use App\Entity\PoubelleConnectee;

#[ORM\Entity(repositoryClass: ObjetConnecteRepository::class)]
class ObjetConnecte
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $idUnique = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $marque = null;

    #[ORM\Column(length: 100)]
    private ?string $etat = null;

    #[ORM\Column(length: 255)]
    private ?string $localisation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $derniereInteraction = null;

    #[ORM\Column(length: 100)]
    private ?string $connectivite = null;

    #[ORM\Column(type: 'integer')]
    private ?int $batteriePct = null;

    #[ORM\Column(type: 'boolean')]
    private ?bool $actif = null;

    #[ORM\OneToMany(mappedBy: 'objet', targetEntity: CameraSurveillance::class, cascade: ['persist', 'remove'])]
    private Collection $cameraSurveillance;

    #[ORM\OneToMany(mappedBy: 'objet', targetEntity: BorneRecharge::class, cascade: ['persist', 'remove'])]
    private Collection $borneRecharge;

    #[ORM\OneToMany(mappedBy: 'objet', targetEntity: CapteurQualiteAir::class, cascade: ['persist', 'remove'])]
    private Collection $capteurQualiteAir;

    #[ORM\OneToMany(mappedBy: 'objet', targetEntity: FeuCirculation::class, cascade: ['persist', 'remove'])]
    private Collection $feuCirculation;

    #[ORM\OneToMany(mappedBy: 'objet', targetEntity: LampadaireIntelligent::class, cascade: ['persist', 'remove'])]
    private Collection $lampadaireIntelligent;

    #[ORM\OneToMany(mappedBy: 'objet', targetEntity: PoubelleConnectee::class, cascade: ['persist', 'remove'])]
    private Collection $poubelleConnectee;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUnique(): ?string
    {
        return $this->idUnique;
    }

    public function setIdUnique(string $idUnique): self
    {
        $this->idUnique = $idUnique;
        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
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

    public function getDerniereInteraction(): ?\DateTimeInterface
    {
        return $this->derniereInteraction;
    }

    public function setDerniereInteraction(\DateTimeInterface $derniereInteraction): self
    {
        $this->derniereInteraction = $derniereInteraction;
        return $this;
    }

    public function getConnectivite(): ?string
    {
        return $this->connectivite;
    }

    public function setConnectivite(string $connectivite): self
    {
        $this->connectivite = $connectivite;
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
    * @return Collection<int, CameraSurveillance>
    */
    public function getCameraSurveillance(): Collection
    {
        return $this->cameraSurveillance;
    }
    
    public function addCameraSurveillance(CameraSurveillance $cameraSurveillance): self
    {
        if (!$this->cameraSurveillance->contains($cameraSurveillance)) {
            $this->cameraSurveillance->add($cameraSurveillance);
            $cameraSurveillance->setObjet($this);
        }
    
        return $this;
    }
    
    public function removeCameraSurveillance(CameraSurveillance $cameraSurveillance): self
    {
        if ($this->cameraSurveillance->removeElement($cameraSurveillance)) {
            // set the owning side to null (unless already changed)
            if ($cameraSurveillance->getObjet() === $this) {
                $cameraSurveillance->setObjet(null);
            }
        }
    
        return $this;
    }

    /**
    * @return Collection<int, BorneRecharge>
    */
    public function getBorneRecharge(): Collection
    {
        return $this->borneRecharge;
    }
    
    public function addBorneRecharge(BorneRecharge $borneRecharge): self
    {
        if (!$this->borneRecharge->contains($borneRecharge)) {
            $this->borneRecharge->add($borneRecharge);
            $borneRecharge->setObjet($this);
        }
    
        return $this;
    }
    
    public function removeBorneRecharge(BorneRecharge $borneRecharge): self
    {
        if ($this->borneRecharge->removeElement($borneRecharge)) {
            // set the owning side to null (unless already changed)
            if ($borneRecharge->getObjet() === $this) {
                $borneRecharge->setObjet(null);
            }
        }
    
        return $this;
    }

    /**
    * @return Collection<int, CapteurQualiteAir>
    */
    public function getCapteurQualiteAir(): Collection
    {
        return $this->capteurQualiteAir;
    }
    
    public function addCapteurQualiteAir(CapteurQualiteAir $capteurQualiteAir): self
    {
        if (!$this->capteurQualiteAir->contains($capteurQualiteAir)) {
            $this->capteurQualiteAir->add($capteurQualiteAir);
            $capteurQualiteAir->setObjet($this);
        }
    
        return $this;
    }
    
    public function removeCapteurQualiteAir(CapteurQualiteAir $capteurQualiteAir): self
    {
        if ($this->capteurQualiteAir->removeElement($capteurQualiteAir)) {
            // set the owning side to null (unless already changed)
            if ($capteurQualiteAir->getObjet() === $this) {
                $capteurQualiteAir->setObjet(null);
            }
        }
    
        return $this;
    }


    /**
    * @return Collection<int, FeuCirculation>
    */
    public function getFeuCirculation(): Collection
    {
        return $this->feuCirculation;
    }
    
    public function addFeuCirculation(FeuCirculation $feuCirculation): self
    {
        if (!$this->feuCirculation->contains($feuCirculation)) {
            $this->feuCirculation->add($feuCirculation);
            $feuCirculation->setObjet($this);
        }
    
        return $this;
    }
    
    public function removeFeuCirculation(FeuCirculation $FeuCirculation): self
    {
        if ($this->feuCirculation->removeElement($FeuCirculation)) {
            // set the owning side to null (unless already changed)
            if ($FeuCirculation->getObjet() === $this) {
                $FeuCirculation->setObjet(null);
            }
        }
    
        return $this;
    }

    /**
    * @return Collection<int, LampadaireIntelligent>
    */
    public function getLampadaireIntelligent(): Collection
    {
        return $this->lampadaireIntelligent;
    }
    
    public function addLampadaireIntelligent(LampadaireIntelligent $lampadaireIntelligent): self
    {
        if (!$this->lampadaireIntelligent->contains($lampadaireIntelligent)) {
            $this->lampadaireIntelligent->add($lampadaireIntelligent);
            $lampadaireIntelligent->setObjet($this);
        }
    
        return $this;
    }
    
    public function removeLampadaireIntelligent(LampadaireIntelligent $lampadaireIntelligent): self
    {
        if ($this->lampadaireIntelligent->removeElement($lampadaireIntelligent)) {
            // set the owning side to null (unless already changed)
            if ($lampadaireIntelligent->getObjet() === $this) {
                $lampadaireIntelligent->setObjet(null);
            }
        }
    
        return $this;
    }

        /**
    * @return Collection<int, PoubelleConnectee>
    */
    public function getPoubelleConnectee(): Collection
    {
        return $this->PoubelleConnectee;
    }
    
    public function addPoubelleConnectee(PoubelleConnectee $PoubelleConnectee): self
    {
        if (!$this->PoubelleConnectee->contains($PoubelleConnectee)) {
            $this->PoubelleConnectee->add($PoubelleConnectee);
            $PoubelleConnectee->setObjet($this);
        }
    
        return $this;
    }
    
    public function removePoubelleConnectee(PoubelleConnectee $PoubelleConnectee): self
    {
        if ($this->PoubelleConnectee->removeElement($PoubelleConnectee)) {
            // set the owning side to null (unless already changed)
            if ($PoubelleConnectee->getObjet() === $this) {
                $PoubelleConnectee->setObjet(null);
            }
        }
    
        return $this;
    }


}


