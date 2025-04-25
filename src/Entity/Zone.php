<?php

namespace App\Entity;

use App\Repository\ZoneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ZoneRepository::class)]
class Zone
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null; // quartier, rue, carrefour, parc, etc.

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $coordonnees = null; // Coordonnées géographiques

    #[ORM\OneToMany(mappedBy: 'zone', targetEntity: ObjetConnecte::class)]
    private Collection $objetsConnectes;

    public function __construct()
    {
        $this->objetsConnectes = new ArrayCollection();
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

    public function getCoordonnees(): ?string
    {
        return $this->coordonnees;
    }

    public function setCoordonnees(?string $coordonnees): self
    {
        $this->coordonnees = $coordonnees;
        return $this;
    }

    /**
     * @return Collection<int, ObjetConnecte>
     */
    public function getObjetsConnectes(): Collection
    {
        return $this->objetsConnectes;
    }

    public function addObjetConnecte(ObjetConnecte $objetConnecte): self
    {
        if (!$this->objetsConnectes->contains($objetConnecte)) {
            $this->objetsConnectes->add($objetConnecte);
            $objetConnecte->setZone($this);
        }

        return $this;
    }

    public function removeObjetConnecte(ObjetConnecte $objetConnecte): self
    {
        if ($this->objetsConnectes->removeElement($objetConnecte)) {
            // set the owning side to null (unless already changed)
            if ($objetConnecte->getZone() === $this) {
                $objetConnecte->setZone(null);
            }
        }

        return $this;
    }
}