<?php

namespace App\Entity;

use App\Repository\ObjetConnecteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

use App\Entity\PoubelleConnectee;
use App\Entity\ParkingIntelligent;

/**
 * ObjetConnecte Entity
 * 
 * Cette entité représente un objet connecté générique dans le système de ville intelligente.
 * Elle sert de classe parente pour les différents types d'objets connectés comme les poubelles
 * et les parkings intelligents. Elle stocke les propriétés communes à tous les objets connectés.
 */
#[ORM\Entity(repositoryClass: ObjetConnecteRepository::class)]
#[ORM\HasLifecycleCallbacks] 
class ObjetConnecte
{
    /**
     * Identifiant unique interne
     */
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Identifiant unique lisible par l'humain
     * 
     * Format: [TYPE]-[DATE]-[UNIQID]
     * Ex: POU-20230516-6458a1b2c3d4e
     * 
     * Généré automatiquement avant la persistance de l'entité
     */
    #[ORM\Column(length: 255, unique: true)]
    private ?string $idUnique = null;
   
    /**
     * Génère automatiquement un identifiant unique avant la persistance
     * 
     * Le préfixe est basé sur les 3 premiers caractères du type d'objet,
     * ou 'OBJ' si le type n'est pas défini.
     */   
    #[ORM\PrePersist]
    public function generateIdUnique(): void
    {
        if ($this->idUnique === null) {
            $prefix = $this->type ? strtoupper(substr($this->type, 0, 3)) : 'OBJ';
            $this->idUnique = $prefix . '-' . date('Ymd') . '-' . uniqid();
        }
    }
    
    /**
     * Type d'objet connecté
     */    
    #[ORM\Column(length: 255)]
    private ?string $type = null;

    /**
     * Nom attribué à l'objet connecté
     * 
     * Permet d'identifier facilement l'objet dans l'interface utilisateur
     */
    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    /**
     * Marque ou fabricant de l'objet connecté
     */
    #[ORM\Column(length: 255)]
    private ?string $marque = null;

    /**
     * État actuel de l'objet connecté
     */
    #[ORM\Column(length: 100)]
    private ?string $etat = null;

    /**
     * Localisation géographique ou adresse de l'objet
     */
    #[ORM\Column(length: 255)]
    private ?string $localisation = null;

    /**
     * Niveau de batterie en pourcentage (0-100)
     */
    #[ORM\Column(type: 'integer')]
    private ?int $batteriePct = null;

    /**
     * Indique si l'objet est actuellement actif et opérationnel
     */
    #[ORM\Column(type: 'boolean')]
    private ?bool $actif = null;

    /**
     * Collection de poubelles connectées associées à cet objet
     * 
     * Relation OneToMany, l'objet est le propriétaire de la relation
     */
    #[ORM\OneToMany(mappedBy: 'objet', targetEntity: PoubelleConnectee::class, cascade: ['persist', 'remove'])]
    private Collection $poubelleConnectee;
    
    
    #[ORM\OneToMany(mappedBy: 'objet', targetEntity: ParkingIntelligent::class, cascade: ['persist', 'remove'])]
    private Collection $parkingIntelligent;

    /**
     * Collection de parkings intelligents associés à cet objet
     * 
     * Relation OneToMany, l'objet est le propriétaire de la relation
     */
    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $derniereInteraction = null;

    /**
     * Constructeur
     * 
     * Initialise les collections d'objets associés
     */
    public function __construct()
    {
        $this->poubelleConnectee = new ArrayCollection();
        $this->parkingIntelligent = new ArrayCollection();
        $this->lampadaireIntelligent = new ArrayCollection();
    }
  
    /**
     * Récupère la date et l'heure de la dernière interaction
     * 
     * @return \DateTimeInterface|null La date et l'heure de la dernière interaction ou null si jamais contacté
     */  
    public function getDerniereInteraction(): ?\DateTimeInterface
    {
        return $this->derniereInteraction;
    }

    /**
     * Définit la date et l'heure de la dernière interaction
     * 
     * @param \DateTimeInterface|null $derniereInteraction La nouvelle date de dernière interaction
     * @return self
     */
    public function setDerniereInteraction(?\DateTimeInterface $derniereInteraction): self
    {
        $this->derniereInteraction = $derniereInteraction;
        return $this;
    }

   /**
     * Récupère l'identifiant unique lisible
     * 
     * @return string|null L'identifiant unique au format [TYPE]-[DATE]-[UNIQID]
     */
    public function getIdUnique(): ?string
    {
        return $this->idUnique;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

   /**
     * Récupère le nom de l'objet connecté
     * 
     * @return string|null Le nom de l'objet
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * Définit le nom de l'objet connecté
     * 
     * @param string $nom Le nouveau nom
     * @return self
     */
    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * Récupère le type d'objet connecté
     * 
     * @return string|null Le type d'objet
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Définit le type d'objet connecté
     * 
     * @param string $type Le nouveau type
     * @return static
     */
    public function setType(string $type): static
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Récupère la marque de l'objet connecté
     * 
     * @return string|null La marque ou le fabricant
     */
    public function getMarque(): ?string
    {
        return $this->marque;
    }

    /**
     * Définit la marque de l'objet connecté
     * 
     * @param string $marque La nouvelle marque
     * @return self
     */
    public function setMarque(string $marque): self
    {
        $this->marque = $marque;
        return $this;
    }

    /**
     * Récupère l'état actuel de l'objet connecté
     * 
     * @return string|null L'état de l'objet
     */
    public function getEtat(): ?string
    {
        return $this->etat;
    }

    /**
     * Définit l'état de l'objet connecté
     * 
     * @param string $etat Le nouvel état
     * @return self
     */
    public function setEtat(string $etat): self
    {
        $this->etat = $etat;
        return $this;
    }

    /**
     * Récupère la localisation de l'objet connecté
     * 
     * @return string|null La localisation ou l'adresse
     */
    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    /**
     * Définit la localisation de l'objet connecté
     * 
     * @param string $localisation La nouvelle localisation
     * @return self
     */
    public function setLocalisation(string $localisation): self
    {
        $this->localisation = $localisation;
        return $this;
    }

    /**
     * Récupère le niveau de batterie de l'objet connecté
     * 
     * @return int|null Le pourcentage de batterie (0-100)
     */
    public function getBatteriePct(): ?int
    {
        return $this->batteriePct;
    }

    /**
     * Définit le niveau de batterie de l'objet connecté
     * 
     * @param int $batteriePct Le nouveau pourcentage de batterie
     * @return self
     */
    public function setBatteriePct(int $batteriePct): self
    {
        $this->batteriePct = $batteriePct;
        return $this;
    }

    /**
     * Vérifie si l'objet connecté est actif
     * 
     * @return bool|null true si l'objet est actif, false sinon
     */
    public function isActif(): ?bool
    {
        return $this->actif;
    }

   /**
     * Définit si l'objet connecté est actif
     * 
     * @param bool $actif Le nouveau statut d'activité
     * @return self
     */
    public function setActif(bool $actif): self
    {
        $this->actif = $actif;
        return $this;
    }

    /**
     * Récupère la collection de poubelles connectées associées
     * 
     * @return Collection<int, PoubelleConnectee> La collection de poubelles connectées
     */
    public function getPoubelleConnectee(): Collection
    {
        return $this->poubelleConnectee;
    }
    
    /**
     * Ajoute une poubelle connectée à la collection
     * 
     * Cette méthode maintient la cohérence de la relation bidirectionnelle
     * 
     * @param PoubelleConnectee $poubelleConnectee La poubelle à ajouter
     * @return self
     */    
    public function addPoubelleConnectee(PoubelleConnectee $poubelleConnectee): self
    {
        if (!$this->poubelleConnectee->contains($poubelleConnectee)) {
            $this->poubelleConnectee->add($poubelleConnectee);
            $poubelleConnectee->setObjet($this);
        }
    
        return $this;
    }
    
    /**
     * Retire une poubelle connectée de la collection
     * 
     * Cette méthode maintient la cohérence de la relation bidirectionnelle
     * 
     * @param PoubelleConnectee $poubelleConnectee La poubelle à retirer
     * @return self
     */    
    public function removePoubelleConnectee(PoubelleConnectee $poubelleConnectee): self
    {
        if ($this->poubelleConnectee->removeElement($poubelleConnectee)) {
            // set the owning side to null (unless already changed)
            if ($poubelleConnectee->getObjet() === $this) {
                $poubelleConnectee->setObjet(null);
            }
        }
        
        return $this;
    }
    
    /**
     * Récupère la collection de parkings intelligents associés
     * 
     * @return Collection<int, ParkingIntelligent> La collection de parkings intelligents
     */
    public function getParkingIntelligent(): Collection
    {
        return $this->parkingIntelligent;
    }
    
    /**
     * Ajoute un parking intelligent à la collection
     * 
     * @param ParkingIntelligent $parkingIntelligent Le parking à ajouter
     * @return self
     */    
    public function addParkingIntelligent(ParkingIntelligent $parkingIntelligent): self
    {
        if (!$this->parkingIntelligent->contains($parkingIntelligent)) {
            $this->parkingIntelligent->add($parkingIntelligent);
            $parkingIntelligent->setObjet($this);
        }
    
        return $this;
    }
    
     /**
     * Retire un parking intelligent de la collection
     * 
     * @param ParkingIntelligent $parkingIntelligent Le parking à retirer
     * @return self
     */   
    public function removeParkingIntelligent(ParkingIntelligent $parkingIntelligent): self
    {
        if ($this->parkingIntelligent->removeElement($parkingIntelligent)) {
            // set the owning side to null (unless already changed)
            if ($parkingIntelligent->getObjet() === $this) {
                $parkingIntelligent->setObjet(null);
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
    
}
