<?php

namespace App\Entity;

use App\Repository\BorneRechargeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BorneRechargeRepository::class)]
class BorneRecharge
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'bornesRecharge')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?ObjetConnecte $objet = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $puissanceMax = null;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private ?string $typeConnecteur = null;

    #[ORM\Column(type: 'integer', options: ['default' => 1])]
    private int $nombrePointsCharge = 1;

    #[ORM\Column(type: 'string', length: 20, options: ['default' => 'libre'])]
    private string $statusOccupation = 'libre';

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $prixKwh = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $tempsChargeMoyen = null;

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

    public function getPuissanceMax(): ?float
    {
        return $this->puissanceMax;
    }

    public function setPuissanceMax(?float $puissanceMax): self
    {
        $this->puissanceMax = $puissanceMax;
        return $this;
    }

    public function getTypeConnecteur(): ?string
    {
        return $this->typeConnecteur;
    }

    public function setTypeConnecteur(?string $typeConnecteur): self
    {
        $this->typeConnecteur = $typeConnecteur;
        return $this;
    }

    public function getNombrePointsCharge(): int
    {
        return $this->nombrePointsCharge;
    }

    public function setNombrePointsCharge(int $nombrePointsCharge): self
    {
        $this->nombrePointsCharge = $nombrePointsCharge;
        return $this;
    }

    public function getStatusOccupation(): string
    {
        return $this->statusOccupation;
    }

    public function setStatusOccupation(string $statusOccupation): self
    {
        $this->statusOccupation = $statusOccupation;
        return $this;
    }

    public function getPrixKwh(): ?float
    {
        return $this->prixKwh;
    }

    public function setPrixKwh(?float $prixKwh): self
    {
        $this->prixKwh = $prixKwh;
        return $this;
    }

    public function getTempsChargeMoyen(): ?int
    {
        return $this->tempsChargeMoyen;
    }

    public function setTempsChargeMoyen(?int $tempsChargeMoyen): self
    {
        $this->tempsChargeMoyen = $tempsChargeMoyen;
        return $this;
    }

}
