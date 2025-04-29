<?php

namespace App\Entity;

use App\Repository\HistoriqueConsultationRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Utilisateur;

/**
 * HistoriqueConsultation Entity
 * 
 * Cette entité enregistre l'historique des consultations d'éléments par les utilisateurs.
 * Elle permet de suivre quels éléments ont été consultés, par qui et quand.
 * Utile pour l'analyse des comportements utilisateurs et la génération de statistiques d'usage.
 */
#[ORM\Entity(repositoryClass: HistoriqueConsultationRepository::class)]
class HistoriqueConsultation
{
    /**
     * Identifiant unique de l'entrée d'historique
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Utilisateur ayant effectué la consultation
     * 
     * Relation ManyToOne vers l'entité Utilisateur.
     */
    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $utilisateur = null;

    /**
     * Type d'élément consulté
     * 
     * Représente la catégorie ou le type d'entité de l'élément consulté
     */
    #[ORM\Column(length: 255)]
    private ?string $typeElement = null;

    /**
     * Identifiant de l'élément consulté
     * 
     * Optionnel car certains éléments pourraient ne pas avoir d'identifiant
     * ou la consultation pourrait concerner une liste/catégorie
     */
    #[ORM\Column(nullable: true)]
    private ?int $elementId = null;
   
    /**
     * Nom ou titre de l'élément consulté
     * 
     * Stocké pour éviter d'avoir à recharger l'élément consulté lors de l'affichage
     * de l'historique, notamment si l'élément a été supprimé depuis la consultation
     */   
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nomElement = null; // Nouveau champ pour le nom

    /**
     * Date et heure de la consultation
     * 
     * Utilise DateTimeImmutable pour garantir que la date enregistrée 
     * ne sera pas modifiée accidentellement
     */
    #[ORM\Column]
    private \DateTimeImmutable $dateConsultation;


    /**
     * Récupère le nom de l'élément consulté
     * 
     * @return string|null Le nom de l'élément ou null si non disponible
     */
    public function getNomElement(): ?string
    {
        return $this->nomElement;
    }

    /**
     * Définit le nom de l'élément consulté
     * 
     * @param string|null $nomElement Le nom à définir ou null si non disponible
     * @return self
     */
    public function setNomElement(?string $nomElement): self
    {
        $this->nomElement = $nomElement;
        return $this;
    }
    
    /**
     * Récupère l'utilisateur ayant effectué la consultation
     * 
     * @return Utilisateur|null L'utilisateur concerné
     */
    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    /**
     * Définit l'utilisateur ayant effectué la consultation
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
     * Récupère l'identifiant unique de l'entrée d'historique
     * 
     * @return int|null L'identifiant ou null si non persisté
     */
    public function getId(): ?int
    {
        return $this->id;
    }

   /**
     * Récupère le type d'élément consulté
     * 
     * @return string|null Le type d'élément
     */
    public function getTypeElement(): ?string
    {
        return $this->typeElement;
    }

    /**
     * Définit le type d'élément consulté
     * 
     * @param string $typeElement Le type d'élément à définir
     * @return self
     */
    public function setTypeElement(string $typeElement): self
    {
        $this->typeElement = $typeElement;
        return $this;
    }

    /**
     * Récupère l'identifiant de l'élément consulté
     * 
     * @return int|null L'identifiant de l'élément ou null si non applicable
     */
    public function getElementId(): ?int
    {
        return $this->elementId;
    }

    /**
     * Définit l'identifiant de l'élément consulté
     * 
     * @param int|null $elementId L'identifiant à définir ou null si non applicable
     * @return self
     */
    public function setElementId(?int $elementId): self
    {
        $this->elementId = $elementId;
        return $this;
    }

    /**
     * Récupère la date et l'heure de la consultation
     * 
     * @return \DateTimeImmutable La date et l'heure de consultation
     */
    public function getDateConsultation(): \DateTimeImmutable
    {
        return $this->dateConsultation;
    }

    /**
     * Définit la date et l'heure de la consultation
     * 
     * @param \DateTimeImmutable $dateConsultation La date et l'heure à définir
     * @return self
     */
    public function setDateConsultation(\DateTimeImmutable $dateConsultation): self
    {
        $this->dateConsultation = $dateConsultation;
        return $this;
    }
}
