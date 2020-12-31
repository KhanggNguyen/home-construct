<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DimensionMenuiserieExterieure
 *
 * @ORM\Table(name="home_construct_dimension_menuiserie_exterieure")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\DimensionMenuiserieExterieureRepository")
 */
class DimensionMenuiserieExterieure
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
     * @var float
     *
     * @ORM\Column(name="longueur", type="float", precision=255, scale=0, nullable=false)
     */
    private $longueur;

    /**
     * @var float
     *
     * @ORM\Column(name="largeur", type="float", precision=255, scale=0, nullable=false)
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
     * @return DimensionMenuiserieExterieure
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
     * @return DimensionMenuiserieExterieure
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
        return 'Dimension Menuiserie Exterieure';
    }
}
