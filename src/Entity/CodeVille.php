<?php

namespace App\Entity;

use App\Repository\CodeVilleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * CodeVille Entity
 * 
 * Cette entité représente un code associé à une adresse dans la ville.
 * Elle permet de suivre l'utilisation des codes (utilisés ou non),
 * ainsi que l'utilisateur associé à chaque code.
 */
#[ORM\Entity(repositoryClass: CodeVilleRepository::class)]
class CodeVille
{
    /**
     * Identifiant unique du code ville
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * Code unique identifiant l'adresse
     * Ce champ doit être unique dans la base de données
     */
    #[ORM\Column(type: 'string', length: 50, unique: true)]
    private ?string $code = null;

    /**
     * Adresse physique associée au code
     */
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $adresse = null;

    /**
     * Quartier où se situe l'adresse (optionnel)
     */
    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private ?string $quartier = null;

   /**
     * Date et heure de création du code
     * Initialisée automatiquement lors de la création de l'entité
     */
    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $date_creation = null;

    /**
     * Indique si le code a déjà été utilisé
     * Par défaut, les codes sont marqués comme non utilisés
     */
    #[ORM\Column(type: 'boolean')]
    private bool $est_utilise = false;

    /**
     * Identifiant de l'utilisateur associé au code (optionnel)
     * Référence à un utilisateur sans relation Doctrine explicite
     */
    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $utilisateur_id = null;

    /**
     * Constructeur de l'entité
     * 
     * Initialise la date de création au moment de l'instanciation
     */
    public function __construct()
    {
        $this->date_creation = new \DateTime();
    }

   /**
     * Récupère l'identifiant unique du code ville
     * 
     * @return int|null L'identifiant ou null si non persisté
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Récupère le code unique
     * 
     * @return string|null Le code unique
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * Définit le code unique
     * 
     * @param string $code Le nouveau code à définir
     * @return self
     */
    public function setCode(string $code): self
    {
        $this->code = $code;
        return $this;
    }


    /**
     * Récupère l'adresse associée au code
     * 
     * @return string|null L'adresse complète
     */
    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    /**
     * Définit l'adresse associée au code
     * 
     * @param string $adresse La nouvelle adresse
     * @return self
     */
    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;
        return $this;
    }

    /**
     * Récupère le quartier où se situe l'adresse
     * 
     * @return string|null Le nom du quartier ou null si non défini
     */
    public function getQuartier(): ?string
    {
        return $this->quartier;
    }

    /**
     * Définit le quartier où se situe l'adresse
     * 
     * @param string|null $quartier Le nouveau quartier ou null pour le supprimer
     * @return self
     */
    public function setQuartier(?string $quartier): self
    {
        $this->quartier = $quartier;
        return $this;
    }

    /**
     * Récupère la date de création du code
     * 
     * @return \DateTimeInterface|null La date et heure de création
     */
    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    /**
     * Définit la date de création du code
     * 
     * @param \DateTimeInterface $date_creation La nouvelle date de création
     * @return self
     */
    public function setDateCreation(\DateTimeInterface $date_creation): self
    {
        $this->date_creation = $date_creation;
        return $this;
    }

    /**
     * Vérifie si le code a déjà été utilisé
     * 
     * @return bool true si le code est utilisé, false sinon
     */
    public function isEstUtilise(): bool
    {
        return $this->est_utilise;
    }

    /**
     * Définit le statut d'utilisation du code
     * 
     * @param bool $est_utilise Le nouveau statut d'utilisation
     * @return self
     */
    public function setEstUtilise(bool $est_utilise): self
    {
        $this->est_utilise = $est_utilise;
        return $this;
    }

    /**
     * Récupère l'identifiant de l'utilisateur associé au code
     * 
     * @return int|null L'ID de l'utilisateur ou null si aucun utilisateur associé
     */
    public function getUtilisateurId(): ?int
    {
        return $this->utilisateur_id;
    }

    /**
     * Associe un utilisateur au code
     * 
     * @param int|null $utilisateur_id L'ID de l'utilisateur à associer ou null pour dissocier
     * @return self
     */
    public function setUtilisateurId(?int $utilisateur_id): self
    {
        $this->utilisateur_id = $utilisateur_id;
        return $this;
    }
}
