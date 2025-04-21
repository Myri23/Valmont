<?php

namespace App\Entity;

use App\Repository\CameraSurveillanceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CameraSurveillanceRepository::class)]
class CameraSurveillance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: ObjetConnecte::class, inversedBy: 'cameras')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?ObjetConnecte $objet = null;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private ?string $resolution = null;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $detection_mouvement = false;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $vision_nocturne = false;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private ?string $angle_vision = null;

    // Getters et setters

    public function getId(): ?int { return $this->id; }

    public function getObjet(): ?ObjetConnecte { return $this->objet; }
    public function setObjet(?ObjetConnecte $objet): self { $this->objet = $objet; return $this; }

    public function getResolution(): ?string { return $this->resolution; }
    public function setResolution(?string $resolution): self { $this->resolution = $resolution; return $this; }

    public function isDetectionMouvement(): bool { return $this->detection_mouvement; }
    public function setDetectionMouvement(bool $detection_mouvement): self { $this->detection_mouvement = $detection_mouvement; return $this; }

    public function isVisionNocturne(): bool { return $this->vision_nocturne; }
    public function setVisionNocturne(bool $vision_nocturne): self { $this->vision_nocturne = $vision_nocturne; return $this; }

    public function getAngleVision(): ?string { return $this->angle_vision; }
    public function setAngleVision(?string $angle_vision): self { $this->angle_vision = $angle_vision; return $this; }
}
