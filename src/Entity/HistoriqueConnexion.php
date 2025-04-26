<?php

// src/Entity/HistoriqueConnexion.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HistoriqueConnexionRepository")
 */
class HistoriqueConnexion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateur")
     * @ORM\JoinColumn(nullable=false)
     */
    private $utilisateur;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateConnexion;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ipConnexion;

    // Getters et Setters pour chaque propriété...
}

