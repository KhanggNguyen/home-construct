<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypePlomberie
 *
 * @ORM\Table(name="home_construct_type_plomberie")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\TypePlomberieRepository")
 */
class TypePlomberie
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @var float|null
     *
     * @ORM\Column(name="prix", type="float", precision=255, scale=0, nullable=true)
     */
    private $prix;

    /**
     * @var float|null
     *
     * @ORM\Column(name="mainOeuvre", type="float", precision=255, scale=0, nullable=true)
     */
    private $mainOeuvre;

    /**
     * @var boolean|null
     *
     * @ORM\Column(name="parM2", type="boolean")
     */
    private $parM2;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set nom.
     *
     * @param string|null $nom
     *
     * @return TypePlomberie
     */
    public function setNom($nom = null)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Set prix.
     *
     * @param float|null $prix
     *
     * @return TypePlomberie
     */
    public function setPrix($prix = null)
    {
        $this->prix = $prix;

        return $this;
    }

    public function __toString(){
        // to show the name of the Category in the select
        return $this->nom;
        // to show the id of the Category in the select
        // return $this->id;
    }

    /**
     * Get prix.
     *
     * @return float|null
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Get nom.
     *
     * @return string|null
     */
    public function getNom()
    {
        return $this->nom;
    }

    public function getEntityName(){
        return 'Type Plomberie';
    }

    /**
     * Set mainOeuvre.
     *
     * @param float|null $mainOeuvre
     *
     * @return TypePlomberie
     */
    public function setMainOeuvre($mainOeuvre = null)
    {
        $this->mainOeuvre = $mainOeuvre;

        return $this;
    }

    /**
     * Get mainOeuvre.
     *
     * @return float|null
     */
    public function getMainOeuvre()
    {
        return $this->mainOeuvre;
    }

    /**
     * Set parM2.
     *
     * @param bool $parM2
     *
     * @return TypePlomberie
     */
    public function setParM2($parM2)
    {
        $this->parM2 = $parM2;

        return $this;
    }

    /**
     * Get parM2.
     *
     * @return bool
     */
    public function getParM2()
    {
        return $this->parM2;
    }
}
