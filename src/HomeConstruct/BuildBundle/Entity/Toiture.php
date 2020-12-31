<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Toiture
 *
 * @ORM\Table(name="home_construct_toiture")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\ToitureRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Toiture
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\TypeCouverture")
     * @ORM\JoinColumn(nullable=false)
     */
    private $typeCouverture;

    /**
     * @var float|null
     *
     * @ORM\Column(name="m2", type="float", precision=255, scale=0, nullable=false)
     */
    private $m2;

    /**
     * @ORM\Column(type="float", nullable=false)
     */
    private $degrePente;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $expoVent;

    /**
     * @var \Charpente
     *
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\Charpente", cascade={"persist","remove"},inversedBy="toiture")
     * @ORM\JoinColumn(nullable=true,onDelete="SET NULL")
     * onDelete=SET NULL veut dire que quand la charpente est supprime la toiture a lattribut charpente egale a null
     */
    private $charpente;

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
     * @var \GrosOeuvre
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\GrosOeuvre", inversedBy="toiture"))
     * @ORM\JoinColumn(nullable=false)
     */
    private $grosOeuvre;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getM2(): ?float
    {
        return $this->m2;
    }

    public function setM2(float $m2): self
    {
        $this->m2 = $m2;

        return $this;
    }

    public function getDegrePente(): ?float
    {
        return $this->degrePente;
    }

    public function setDegrePente(float $degrePente): self
    {
        $this->degrePente = $degrePente;

        return $this;
    }

    public function getExpoVent(): ?bool
    {
        return $this->expoVent;
    }

    public function setExpoVent(bool $expoVent): self
    {
        $this->expoVent = $expoVent;

        return $this;
    }

    /**
     * Set prix.
     *
     * @param float $prix
     *
     * @return Toiture
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Calcule le prix total et le set au prix de la toiture.
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function calculPrix()
    {
        $prixTotalType = ($this->getM2())*($this->getTypeCouverture()->getPrix());
        $prixTotalMainDoeuvre = ($this->getM2())*($this->getTarifMainDoeuvre());
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
     * Set charpente.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Charpente $charpente
     *
     * @return Toiture
     */
    public function setCharpente(Charpente $charpente)
    {
        $this->charpente = $charpente;

        return $this;
    }

    /**
     * Get charpente.
     *
     * @return \HomeConstruct\BuildBundle\Entity\Charpente
     */
    public function getCharpente()
    {
        return $this->charpente;
    }

    public function getTypeCouverture(): ?TypeCouverture
    {
        return $this->typeCouverture;
    }

    public function setTypeCouverture(?TypeCouverture $typeCouverture): self
    {
        $this->typeCouverture = $typeCouverture;

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

    public function setGrosOeuvre(GrosOeuvre $grosOeuvre): self
    {
        $this->grosOeuvre = $grosOeuvre;

        return $this;
    }

    public function getEntityName(){
        return 'Toiture';
    }
}
