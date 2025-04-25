<?php

namespace App\Entity;

use App\Repository\PoubelleConnecteeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PoubelleConnecteeRepository::class)]
class PoubelleConnectee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'poubellesConnectees')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?ObjetConnecte $objet = null;

    #[ORM\Column(type: 'integer')]
    private int $niveauRemplissage;

    #[ORM\Column(type: 'integer')]
    private int $capaciteTotale;

    #[ORM\Column(type: 'string', length: 20, options: ['default' => 'mixte'])]
    private string $typeDechets = 'mixte';

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $derniereCollecte = null;

    #[ORM\Column(type: 'boolean')]
    private bool $compacteur = false;

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

    public function getNiveauRemplissage(): int
    {
        return $this->niveauRemplissage;
    }

    public function setNiveauRemplissage(int $niveauRemplissage): self
    {
        $this->niveauRemplissage = $niveauRemplissage;

        return $this;
    }

    public function getCapaciteTotale(): int
    {
        return $this->capaciteTotale;
    }

    public function setCapaciteTotale(int $capaciteTotale): self
    {
        $this->capaciteTotale = $capaciteTotale;

        return $this;
    }

    public function getTypeDechets(): string
    {
        return $this->typeDechets;
    }

    public function setTypeDechets(string $typeDechets): self
    {
        $this->typeDechets = $typeDechets;

        return $this;
    }

    public function getDerniereCollecte(): ?\DateTimeInterface
    {
        return $this->derniereCollecte;
    }

    public function setDerniereCollecte(?\DateTimeInterface $derniereCollecte): self
    {
        $this->derniereCollecte = $derniereCollecte;

        return $this;
    }

    public function isCompacteur(): bool
    {
        return $this->compacteur;
    }

    public function setCompacteur(bool $compacteur): self
    {
        $this->compacteur = $compacteur;

        return $this;
    }

}
