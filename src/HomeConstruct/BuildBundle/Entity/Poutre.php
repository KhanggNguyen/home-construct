<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Poutre
 *
 * @ORM\Table(name="home_construct_poutre")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\PoutreRepository")
 */
class Poutre
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var float|null
     *
     * @ORM\Column(name="largeur", type="float", precision=255, scale=0, nullable=true)
     */
    private $largeur;


    /**
     * @var float|null
     *
     * @ORM\Column(name="longueur", type="float", precision=255, scale=0, nullable=true)
     */
    private $longueur;

    /**
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\TypeMateriauxPoutre", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\Elevation", cascade={"persist"},inversedBy="poutre")
     * @ORM\JoinColumn(nullable=false)
     */
    private $elevation;



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
     * Get type.
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get elevation.
     *
     * @return int
     */
    public function getElevation()
    {
        return $this->elevation;
    }

    /**
     * Get longueur.
     *
     * @return float
     */
    public function getLongueur()
    {
        return $this->longueur;
    }

    /**
     * Get largeur.
     *
     * @return float
     */
    public function getLargeur()
    {
        return $this->largeur;
    }


    /**
     * Set largeur.
     *
     * @param float $largeur
     *
     * @return Poutre
     */
    public function setLargeur($largeur)
    {
        $this->largeur=$largeur;

        return $this;
    }

    /**
     * Set longueur.
     *
     * @param float $longueur
     *
     * @return Poutre
     */
    public function setLongueur($longueur)
    {
        $this->longueur=$longueur;

        return $this;
    }

    /**
     * Set type.
     *
     * @param int $id
     *
     * @return Poutre
     */
    public function setType($id)
    {
        $this->type=$id;

        return $this;
    }

    /**
     * Set elevation.
     *
     * @param int $id
     *
     * @return Poutre
     */

    public function setElevation($id)
    {
        $this->elevation=$id;

        return $this;
    }

    /**
     * Calculer Prix des Murs
     *
     * @ORM\PrePersist
     * @ORM\PostUpdate
     */
    public function calculPrix(){
        $prixPoutre = $this->getType()->getPrix() * $this->getLargeur()*$this->getLongueur();
        return $prixPoutre;

    }


    public function getEntityName(){
        return 'Poutre';
    }

}
