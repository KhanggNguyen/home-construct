<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AppuisFenetre
 *
 * @ORM\Table(name="home_construct_appuis_fenetre")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\AppuisFenetreRepository")
 */
class AppuisFenetre
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
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\TypeAppuisFenetre", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\Elevation", cascade={"persist"},inversedBy="appuisFenetre")
     * @ORM\JoinColumn(nullable=false)
     */
    private $elevation;

    /**
     * @var int|null
     *
     * @ORM\Column(name="quantite", type="integer", nullable=true)
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
     * @param \HomeConstruct\BuildBundle\Entity\TypeAppuisFenetre $type
     *
     * @return AppuisFenetre
     */
    public function setType(\HomeConstruct\BuildBundle\Entity\TypeAppuisFenetre $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return \HomeConstruct\BuildBundle\Entity\TypeAppuisFenetre
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set quantite.
     *
     * @param int|null $quantite
     *
     * @return AppuisFenetre
     */
    public function setQuantite($quantite = null)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite.
     *
     * @return int|null
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * Set elevation.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Elevation $elevation
     *
     * @return AppuisFenetre
     */
    public function setElevation(\HomeConstruct\BuildBundle\Entity\Elevation $elevation)
    {
        $this->elevation = $elevation;

        return $this;
    }

    /**
     * Get elevation.
     *
     * @return \HomeConstruct\BuildBundle\Entity\Elevation
     */
    public function getElevation()
    {
        return $this->elevation;
    }

    /**
     * Calculer Prix des Murs
     *
     * @ORM\PrePersist
     * @ORM\PostUpdate
     */
    public function calculPrix(){
        $prixAppuis = $this->getType()->getPrix()*$this->getQuantite();
        return $prixAppuis;
    }

    public function getEntityName(){
        return 'Appuis Fenetre';
    }
}
