<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Charpente
 *
 * @ORM\Table(name="home_construct_charpente")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\CharpenteRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Charpente
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
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\TypeCharpente")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\FormeCharpente")
     * @ORM\JoinColumn(nullable=false)
     */
    private $forme;

    /**
     * @var float
     *
     * @ORM\Column(type="float", precision=255, scale=0, nullable=false)
     */
    private $tarifMainDoeuvre;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float", precision=255, scale=0, nullable=true)
     */
    private $prix;

    /**
     * @var \Toiture
     *
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\Toiture", cascade={"persist"}, mappedBy="charpente")
     * @ORM\JoinColumn(nullable=false,onDelete="CASCADE")
     * onDelete=CASCADE veut dire que quand la toiture est supprime la charpente aussi
     */
    private $toiture;

    /**
     * @var \GrosOeuvre
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\GrosOeuvre", inversedBy="charpente"))
     * @ORM\JoinColumn(nullable=false)
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
     * Set prix.
     *
     * @param float $prix
     *
     * @return Charpente
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Calcule le prix total et le set au prix de la charpente.
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function calculPrix()
    {
        $prixTotalType = ($this->getToiture()->getM2())*($this->getType()->getPrix());
        $prixTotalMainDoeuvre = ($this->getToiture()->getM2())*($this->getTarifMainDoeuvre());

        $this->setPrix($prixTotalType+$prixTotalMainDoeuvre);
    }

    /**
     * Calculer le prix du gros oeuvre
     *
     * @ORM\PostPersist
     * @ORM\PostUpdate
     */
    public function appelCalculPrixGO(){
        $grosOeuvre=$this->getGrosOeuvre();
        $grosOeuvre->calculPrix();
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

    /**
     * Set type.
     *
     * @param \HomeConstruct\BuildBundle\Entity\TypeCharpente $type
     *
     * @return Charpente
     */
    public function setType(TypeCharpente $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return \HomeConstruct\BuildBundle\Entity\TypeCharpente
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set forme.
     *
     * @param \HomeConstruct\BuildBundle\Entity\FormeCharpente $forme
     *
     * @return Charpente
     */
    public function setForme(\HomeConstruct\BuildBundle\Entity\FormeCharpente $forme)
    {
        $this->forme = $forme;

        return $this;
    }

    /**
     * Get forme.
     *
     * @return \HomeConstruct\BuildBundle\Entity\FormeCharpente
     */
    public function getForme()
    {
        return $this->forme;
    }

    public function getToiture(): ?Toiture
    {
        return $this->toiture;
    }

    public function setToiture(?Toiture $toiture): self
    {
        $this->toiture = $toiture;

        return $this;
    }

    public function getTarifMainDoeuvre(): ?float
    {
        return $this->tarifMainDoeuvre;
    }

    public function setTarifMainDoeuvre(float $tarifMainDoeuvre): self
    {
        $this->tarifMainDoeuvre = $tarifMainDoeuvre;

        return $this;
    }

    public function getGrosOeuvre(): ?GrosOeuvre
    {
        return $this->grosOeuvre;
    }

    public function setGrosOeuvre(?GrosOeuvre $grosOeuvre): self
    {
        $this->grosOeuvre = $grosOeuvre;

        return $this;
    }

    public function getEntityName(){
        return 'Charpente';
    }
}
