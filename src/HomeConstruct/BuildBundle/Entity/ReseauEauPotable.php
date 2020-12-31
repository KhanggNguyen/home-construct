<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ReseauEauPotable
 * @ORM\Table(name="home_construct_reseau_eau_potable")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\ReseauEauPotableRepository")
 */
class ReseauEauPotable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=false)
     */
    private $distance;

    /**
     * @ORM\Column(type="float", nullable=false)
     */
    private $tailleTuyaux;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $prix;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDistance(): ?float
    {
        return $this->distance;
    }

    public function setDistance(float $distance): self
    {
        $this->distance = $distance;

        return $this;
    }

    public function getTailleTuyaux(): ?float
    {
        return $this->tailleTuyaux;
    }

    public function setTailleTuyaux(float $tailleTuyaux): self
    {
        $this->tailleTuyaux = $tailleTuyaux;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Calcule le prix total et le set au prix du reseauEauPotable.
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function calculPrix()
    {

    }

    public function getEntityName(){
        return 'Reseau Eau Potable';
    }
}
