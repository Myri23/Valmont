<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reservation Entity
 * 
 * Cette entité représente une réservation dans le système.
 * Elle stocke les informations de base pour une réservation telles que
 * le nom de la personne, son email et le nombre de places réservées.
 */
#[ORM\Entity]
class Reservation
{
    /**
     * Identifiant unique de la réservation
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Nom de la personne effectuant la réservation
     */
    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    /**
     * Adresse email de contact pour la réservation
     * 
     * Utilisée pour les confirmations et communications concernant la réservation
     */
    #[ORM\Column(length: 255)]
    private ?string $email = null;

    /**
     * Nombre de places réservées
     */
    #[ORM\Column]
    private ?int $nombrePlaces = null;

    /**
     * Récupère l'identifiant unique de la réservation
     * 
     * @return int|null L'identifiant ou null si non persisté
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Récupère le nom de la personne ayant effectué la réservation
     * 
     * @return string|null Le nom de la personne
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * Définit le nom de la personne effectuant la réservation
     * 
     * @param string $nom Le nom de la personne
     * @return self
     */
    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * Récupère l'adresse email de contact pour la réservation
     * 
     * @return string|null L'adresse email
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Définit l'adresse email de contact pour la réservation
     * 
     * @param string $email L'adresse email
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Récupère le nombre de places réservées
     * 
     * @return int|null Le nombre de places
     */
    public function getNombrePlaces(): ?int
    {
        return $this->nombrePlaces;
    }

    /**
     * Définit le nombre de places à réserver
     * 
     * @param int $nombrePlaces Le nombre de places
     * @return self
     */
    public function setNombrePlaces(int $nombrePlaces): self
    {
        $this->nombrePlaces = $nombrePlaces;
        return $this;
    }
}

