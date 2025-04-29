<?php

namespace App\Entity;

use App\Repository\ReservationParkingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationParkingRepository::class)]
class ReservationParking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: ParkingIntelligent::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?ParkingIntelligent $parking = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $utilisateurNom = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $dateReservation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParking(): ?ParkingIntelligent
    {
        return $this->parking;
    }

    public function setParking(?ParkingIntelligent $parking): self
    {
        $this->parking = $parking;

        return $this;
    }

    public function getUtilisateurNom(): ?string
    {
        return $this->utilisateurNom;
    }

    public function setUtilisateurNom(?string $utilisateurNom): self
    {
        $this->utilisateurNom = $utilisateurNom;

        return $this;
    }

    public function getDateReservation(): ?\DateTimeInterface
    {
        return $this->dateReservation;
    }

    public function setDateReservation(\DateTimeInterface $dateReservation): self
    {
        $this->dateReservation = $dateReservation;

        return $this;
    }
}
