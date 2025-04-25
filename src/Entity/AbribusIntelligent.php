<?php

namespace App\Entity;

use App\Repository\AbribusIntelligentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AbribusIntelligentRepository::class)]
class AbribusIntelligent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: ObjetConnecte::class, inversedBy: 'abribusIntelligents')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?ObjetConnecte $objet = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $prochains_passages = null;

    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    private bool $ecran_fonctionnel = true;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $informations_affichees = null;

    // Getters et setters

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

    public function getProchainPassages(): ?string
    {
        return $this->prochains_passages;
    }

    public function setProchainPassages(?string $prochains_passages): self
    {
        $this->prochains_passages = $prochains_passages;
        return $this;
    }

    public function isEcranFonctionnel(): bool
    {
        return $this->ecran_fonctionnel;
    }

    public function setEcranFonctionnel(bool $ecran_fonctionnel): self
    {
        $this->ecran_fonctionnel = $ecran_fonctionnel;
        return $this;
    }

    public function getInformationsAffichees(): ?string
    {
        return $this->informations_affichees;
    }

    public function setInformationsAffichees(?string $informations_affichees): self
    {
        $this->informations_affichees = $informations_affichees;
        return $this;
    }
}