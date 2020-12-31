<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="home_construct_profondeur_recommande")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\ProfondeurRecommandeRepository")
 */
class ProfondeurRecommande
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\FourchetteValeurProfondeur")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fourchetteValeur;

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

    public function getFourchetteValeur(): ?FourchetteValeurProfondeur
    {
        return $this->fourchetteValeur;
    }

    public function setFourchetteValeur(?FourchetteValeurProfondeur $fourchetteValeur): self
    {
        $this->fourchetteValeur = $fourchetteValeur;

        return $this;
    }

    public function __toString(){
        // to show the name of the Category in the select
        return $this->nom;
        // to show the id of the Category in the select
        // return $this->id;
    }

    public function getEntityName(){
        return 'Profondeur Recommande';
    }

}
