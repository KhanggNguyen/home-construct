<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ReseauGazNaturel
 * @ORM\Table(name="home_construct_reseau_gaz_naturel")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\ReseauGazNaturelRepository")
 */
class ReseauGazNaturel
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float",nullable=true)
     */
    private $prixForfait;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrixForfait(): ?float
    {
        return $this->prixForfait;
    }

    public function setPrixForfait(float $prix): self
    {
        $this->prixForfait = $prix;

        return $this;
    }

    public function getEntityName(){
        return 'Reseau Gaz Naturel';
    }

}
