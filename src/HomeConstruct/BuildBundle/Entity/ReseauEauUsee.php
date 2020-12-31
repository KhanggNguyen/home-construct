<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ReseauEauUsee
 * @ORM\Table(name="home_construct_reseau_eau_usee")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\ReseauEauUseeRepository")
 */
class ReseauEauUsee
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $pompeRelevage;

    /**
     * @ORM\Column(type="boolean")
     */
    private $fosseSeptique;

    /**
     * @ORM\Column(type="boolean")
     */
    private $microStation;

    /**
     * @ORM\Column(type="boolean")
     */
    private $etudeHydro;

    /**
     * @ORM\Column(type="float",nullable=true)
     */
    private $prix;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPompeRelevage(): ?bool
    {
        return $this->pompeRelevage;
    }

    public function setPompeRelevage(bool $pompeRelevage): self
    {
        $this->pompeRelevage = $pompeRelevage;

        return $this;
    }

    public function getFosseSeptique(): ?bool
    {
        return $this->fosseSeptique;
    }

    public function setFosseSeptique(bool $fosseSeptique): self
    {
        $this->fosseSeptique = $fosseSeptique;

        return $this;
    }

    public function getMicroStation(): ?bool
    {
        return $this->microStation;
    }

    public function setMicroStation(bool $microStation): self
    {
        $this->microStation = $microStation;

        return $this;
    }

    public function getEtudeHydro(): ?bool
    {
        return $this->etudeHydro;
    }

    public function setEtudeHydro(bool $etudeHydro): self
    {
        $this->etudeHydro = $etudeHydro;

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

    public function getEntityName(){
        return 'Reseau Eau Usee';
    }
}
