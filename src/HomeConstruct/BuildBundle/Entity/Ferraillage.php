<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ferraillage
 *
 * @ORM\Table(name="home_construct_ferraillage")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\FerraillageRepository")
 */
class Ferraillage
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
     * @var \TypeFerraillage|null
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\TypeFerraillage", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $type;

    /**
     * @var int
     * Quantité de ferraillage (en m²) calculé par rapport à la surface
     * de la maison (informationBase du GrosOeuvre)
     * @ORM\Column(name="quantite", type="integer")
     */
    private $quantite;


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
     * Set type.
     *
     * @param \stdClass $type
     *
     * @return Ferraillage
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return \stdClass
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set quantite.
     *
     * @param int $quantite
     *
     * @return Ferraillage
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite.
     *
     * @return int
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    public function getEntityName(){
        return 'Ferraillage';
    }
}
