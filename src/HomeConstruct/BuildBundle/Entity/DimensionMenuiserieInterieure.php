<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DimensionMenuiserieInterieure
 *
 * @ORM\Table(name="home_construct_dimension_menuiserie_interieure")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\DimensionMenuiserieInterieureRepository")
 */
class DimensionMenuiserieInterieure
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
     * @var float|null
     *
     * @ORM\Column(name="longueur", type="float", precision=255, scale=0, nullable=true)
     */
    private $longueur;

    /**
     * @var float|null
     *
     * @ORM\Column(name="largeur", type="float", precision=255, scale=0, nullable=true)
     */
    private $largeur;



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
     * Set longueur.
     *
     * @param float|null $longueur
     *
     * @return DimensionMenuiserieInterieure
     */
    public function setLongueur($longueur = null)
    {
        $this->longueur = $longueur;

        return $this;
    }

    /**
     * Get longueur.
     *
     * @return float|null
     */
    public function getLongueur()
    {
        return $this->longueur;
    }

    /**
     * Set largeur.
     *
     * @param float|null $largeur
     *
     * @return DimensionMenuiserieInterieure
     */
    public function setLargeur($largeur = null)
    {
        $this->largeur = $largeur;

        return $this;
    }

    /**
     * Get largeur.
     *
     * @return float|null
     */
    public function getLargeur()
    {
        return $this->largeur;
    }

    public function getEntityName(){
        return 'Dimension Menuiserie Interieure';
    }
}
