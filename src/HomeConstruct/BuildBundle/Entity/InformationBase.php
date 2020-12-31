<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InformationBase
 *
 * @ORM\Table(name="home_construct_information_base")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\InformationBaseRepository")
 */
class InformationBase
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
     * @ORM\Column(name="surface_totale", type="float", precision=255, scale=0, nullable=false)
     */
    private $surfaceTotale;

    /**
     * @var int
     *
     * @ORM\Column(name="nb_pieces", type="integer", nullable=false)
     */
    private $nbPieces;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="comble", type="boolean", nullable=false)
     */
    private $comble;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="sous_sol", type="boolean", nullable=false)
     */
    private $sousSol;

    /**
     * @var \Assainissement
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\Assainissement", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $assainissement;

    /**
     * @var \GrosOeuvre
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\GrosOeuvre", inversedBy="informationBase"))
     * @ORM\JoinColumn(nullable=false,onDelete="CASCADE")
     */
    private $grosOeuvre;



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
     * Set surfaceTotale.
     *
     * @param float|null $surfaceTotale
     *
     * @return InformationBase
     */
    public function setSurfaceTotale($surfaceTotale = null)
    {
        $this->surfaceTotale = $surfaceTotale;

        return $this;
    }

    /**
     * Get surfaceTotale.
     *
     * @return float|null
     */
    public function getSurfaceTotale()
    {
        return $this->surfaceTotale;
    }

    /**
     * Set nbPieces.
     *
     * @param int|null $nbPieces
     *
     * @return InformationBase
     */
    public function setNbPieces($nbPieces = null)
    {
        $this->nbPieces = $nbPieces;

        return $this;
    }

    /**
     * Get nbPieces.
     *
     * @return int|null
     */
    public function getNbPieces()
    {
        return $this->nbPieces;
    }

    /**
     * Set comble.
     *
     * @param bool|null $comble
     *
     * @return InformationBase
     */
    public function setComble($comble = null)
    {
        $this->comble = $comble;

        return $this;
    }

    /**
     * Get comble.
     *
     * @return bool|null
     */
    public function getComble()
    {
        return $this->comble;
    }

    /**
     * Set sousSol.
     *
     * @param bool|null $sousSol
     *
     * @return InformationBase
     */
    public function setSousSol($sousSol = null)
    {
        $this->sousSol = $sousSol;

        return $this;
    }

    /**
     * Get sousSol.
     *
     * @return bool|null
     */
    public function getSousSol()
    {
        return $this->sousSol;
    }

    /**
     * Set assainissement.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Assainissement $assainissement
     *
     * @return InformationBase
     */
    public function setAssainissement(\HomeConstruct\BuildBundle\Entity\Assainissement $assainissement)
    {
        $this->assainissement = $assainissement;

        return $this;
    }

    /**
     * Get assainissement.
     *
     * @return \HomeConstruct\BuildBundle\Entity\Assainissement
     */
    public function getAssainissement()
    {
        return $this->assainissement;
    }

    public function getGrosOeuvre(): ?GrosOeuvre
    {
        return $this->grosOeuvre;
    }

    public function setGrosOeuvre(GrosOeuvre $grosOeuvre): self
    {
        $this->grosOeuvre = $grosOeuvre;

        return $this;
    }

    public function getEntityName(){
        return 'Information Base';
    }
}
