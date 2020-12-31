<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * MaterielExcavation
 *
 * @ORM\Table(name="home_construct_materiel_excavation")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\MaterielExcavationRepository")
 */
class MaterielExcavation
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
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var float | null
     *
     * @ORM\Column(name="prix", type="float", nullable=true)
     *
     */
    private $prix;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="HomeConstruct\BuildBundle\Entity\Excavation", mappedBy="materiels")
     */
    private $excavations;


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
     * @param string $nom
     *
     * @return MaterielExcavation
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom.
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->excavations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add excavation.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Excavation $excavation
     *
     * @return MaterielExcavation
     */
    public function addExcavation(\HomeConstruct\BuildBundle\Entity\Excavation $excavation)
    {
        $this->excavations[] = $excavation;

        return $this;
    }

    /**
     * Remove excavation.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Excavation $excavation
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeExcavation(\HomeConstruct\BuildBundle\Entity\Excavation $excavation)
    {
        return $this->excavations->removeElement($excavation);
    }

    /**
     * Get excavations.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExcavations()
    {
        return $this->excavations;
    }

    /**
     * Set prix.
     *
     * @param float $prix
     *
     * @return MaterielExcavation
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix.
     *
     * @return float
     */
    public function getPrix()
    {
        return $this->prix;
    }

    public function getEntityName(){
        return 'Materiel Excavation';
    }
}
