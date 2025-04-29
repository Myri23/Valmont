<?php

namespace App\Entity;

use App\Repository\PoubelleConnecteeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * PoubelleConnectee Entity
 * 
 * Cette entité représente une poubelle connectée dans le système de ville intelligente.
 * Elle stocke les informations spécifiques aux poubelles telles que le niveau de remplissage,
 * la capacité, le type de déchets et les informations de collecte. Cette entité est liée à un 
 * ObjetConnecte qui contient les propriétés communes à tous les objets connectés.
 */
#[ORM\Entity(repositoryClass: PoubelleConnecteeRepository::class)]
class PoubelleConnectee
{
    /**
     * Identifiant unique de la poubelle connectée
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * Objet connecté associé à cette poubelle
     * 
     * Relation ManyToOne vers l'entité ObjetConnecte.
     * Cette relation est configurée pour supprimer en cascade les poubelles
     * lorsque l'objet connecté associé est supprimé.
     */
    #[ORM\ManyToOne(inversedBy: 'poubelleConnectee')]
    #[ORM\JoinColumn(name: "objet_id", referencedColumnName: "id", nullable: false, onDelete: 'CASCADE')]
    private ?ObjetConnecte $objet = null;

    /**
     * Niveau de remplissage actuel de la poubelle
     */
    #[ORM\Column(type: 'integer')]
    private int $niveauRemplissage;

    /**
     * Capacité totale de la poubelle
     */
    #[ORM\Column(type: 'integer')]
    private int $capaciteTotale;

    /**
     * Type de déchets acceptés par la poubelle
     */
    #[ORM\Column(type: 'string', length: 20, options: ['default' => 'mixte'])]
    private string $typeDechets = 'mixte';

   /**
     * Date et heure de la dernière collecte
     * 
     * Permet de suivre quand la poubelle a été vidée pour la dernière fois.
     */
    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $derniereCollecte = null;

    /**
     * Indique si la poubelle dispose d'un compacteur
     * 
     * Un compacteur permet d'augmenter la capacité effective en compressant les déchets.
     */
    #[ORM\Column(type: 'boolean')]
    private bool $compacteur = false;

    /**
     * Récupère l'identifiant unique de la poubelle
     * @return int|null L'identifiant unique ou null
     */
    public function getIdUnique(): ?int
    {
        return $this->idUnique;
    }

    /**
     * Récupère l'objet connecté associé à cette poubelle
     * 
     * @return ObjetConnecte|null L'objet connecté associé
     */
    public function getObjet(): ?ObjetConnecte
    {
        return $this->objet;
    }

    /**
     * Définit l'objet connecté associé à cette poubelle
     * 
     * @param ObjetConnecte|null $objet L'objet connecté à associer
     * @return self
     */
    public function setObjet(?ObjetConnecte $objet): self
    {
        $this->objet = $objet;

        return $this;
    }

    /**
     * Récupère le niveau de remplissage actuel de la poubelle
     * 
     * @return int Le niveau de remplissage
     */
    public function getNiveauRemplissage(): int
    {
        return $this->niveauRemplissage;
    }

    /**
     * Définit le niveau de remplissage actuel de la poubelle
     * 
     * @param int $niveauRemplissage Le nouveau niveau de remplissage
     * @return self
     */
    public function setNiveauRemplissage(int $niveauRemplissage): self
    {
        $this->niveauRemplissage = $niveauRemplissage;

        return $this;
    }

    /**
     * Récupère la capacité totale de la poubelle
     * 
     * @return int La capacité totale
     */
    public function getCapaciteTotale(): int
    {
        return $this->capaciteTotale;
    }

    /**
     * Définit la capacité totale de la poubelle
     * 
     * @param int $capaciteTotale La nouvelle capacité totale
     * @return self
     */
    public function setCapaciteTotale(int $capaciteTotale): self
    {
        $this->capaciteTotale = $capaciteTotale;

        return $this;
    }

    /**
     * Récupère le type de déchets acceptés par la poubelle
     * 
     * @return string Le type de déchets
     */
    public function getTypeDechets(): string
    {
        return $this->typeDechets;
    }

    /**
     * Définit le type de déchets acceptés par la poubelle
     * 
     * @param string $typeDechets Le nouveau type de déchets
     * @return self
     */
    public function setTypeDechets(string $typeDechets): self
    {
        $this->typeDechets = $typeDechets;

        return $this;
    }

    /**
     * Récupère la date et l'heure de la dernière collecte
     * 
     * @return \DateTimeInterface|null La date et l'heure de la dernière collecte ou null si jamais collectée
     */
    public function getDerniereCollecte(): ?\DateTimeInterface
    {
        return $this->derniereCollecte;
    }

    /**
     * Définit la date et l'heure de la dernière collecte
     * 
     * @param \DateTimeInterface|null $derniereCollecte La nouvelle date de dernière collecte ou null
     * @return self
     */
    public function setDerniereCollecte(?\DateTimeInterface $derniereCollecte): self
    {
        $this->derniereCollecte = $derniereCollecte;

        return $this;
    }

    /**
     * Définit si la poubelle dispose d'un compacteur
     * 
     * @param bool $compacteur Le nouveau statut du compacteur
     * @return self
     */
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
