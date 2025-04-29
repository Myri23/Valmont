<?php

namespace App\Entity;

use App\Repository\HistoriqueConnexionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Cette entité enregistre l'historique des connexions des utilisateurs.
 * Elle permet de suivre quand et depuis quelle adresse IP un utilisateur s'est connecté.
 * Ces informations sont utiles pour l'audit de sécurité et le suivi d'activité.
 */
#[ORM\Entity(repositoryClass: HistoriqueConnexionRepository::class)]
class HistoriqueConnexion
{
    /**
     * Identifiant unique de l'entrée d'historique
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    /**
     * Utilisateur associé à cette connexion
     * 
     * Relation ManyToOne vers l'entité Utilisateur.
     * La relation est configurée pour supprimer en cascade les entrées d'historique
     * lorsque l'utilisateur associé est supprimé.
     */
    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private $utilisateur;

  /**
     * Date et heure de la connexion
     */
    #[ORM\Column(type: 'datetime')]
    private $dateConnexion;

    /**
     * Adresse IP depuis laquelle la connexion a été effectuée
     * 
     * Ce champ est optionnel car l'IP peut ne pas être disponible dans certains contextes.
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $ipConnexion;

   /**
     * Récupère l'identifiant unique de l'entrée d'historique
     * 
     * @return int|null L'identifiant ou null si non persisté
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Récupère l'utilisateur associé à cette connexion
     * 
     * @return Utilisateur|null L'utilisateur associé
     */
    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    /**
     * Définit l'utilisateur associé à cette connexion
     * 
     * @param Utilisateur|null $utilisateur L'utilisateur à associer
     * @return self
     */
    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }

    /**
     * Récupère la date et l'heure de la connexion
     * 
     * @return \DateTimeInterface|null La date et l'heure de connexion
     */
    public function getDateConnexion(): ?\DateTimeInterface
    {
        return $this->dateConnexion;
    }

    /**
     * Définit la date et l'heure de la connexion
     * 
     * @param \DateTimeInterface $dateConnexion La date et l'heure à définir
     * @return self
     */
    public function setDateConnexion(\DateTimeInterface $dateConnexion): self
    {
        $this->dateConnexion = $dateConnexion;
        return $this;
    }

    /**
     * Récupère l'adresse IP de connexion
     * 
     * @return string|null L'adresse IP ou null si non disponible
     */
    public function getIpConnexion(): ?string
    {
        return $this->ipConnexion;
    }

   /**
     * Définit l'adresse IP de connexion
     * 
     * @param string|null $ipConnexion L'adresse IP à définir ou null si non disponible
     * @return self
     */
    public function setIpConnexion(?string $ipConnexion): self
    {
        $this->ipConnexion = $ipConnexion;
        return $this;
    }
}

