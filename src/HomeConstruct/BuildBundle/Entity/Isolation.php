<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Isolation
 *
 * @ORM\Table(name="home_construct_isolation")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\IsolationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Isolation
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
     * @var string
     *
     * @ORM\Column(name="typeIsolation", type="string", nullable=false)
     */
    private $typeIsolation;

    /**
     * @var surfaceMursInterieur
     *
     * @ORM\Column(name="surfaceMursInterieur", type="float", nullable=true)
     */
    private $surfaceMursInterieur;

    /**
     * @var surfaceMursExterieur
     *
     * @ORM\Column(name="surfaceMursExterieur", type="float", nullable=true)
     */
    private $surfaceMursExterieur;

    /**
     * @var surfacePlafond
     *
     * @ORM\Column(name="surfacePlafond", type="float", nullable=true)
     */
    private $surfacePlafond;

    /**
     * @var surfaceSol
     *
     * @ORM\Column(name="surfaceSol", type="float", nullable=true)
     */
    private $surfaceSol;

    /**
     * @var TypeIsolationMur
     *
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\TypeIsolationMur", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $typeIsolationMur;

    /**
     * @var TypeIsolationPlafond
     *
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\TypeIsolationPlafond", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $typeIsolationPlafond;

    /**
     * @var typeIsolationPlancher
     *
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\TypeIsolationPlancher", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $typeIsolationPlancher;

    /**
     * @var typeIsolationVitre
     *
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\TypeIsolationVitre", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $typeIsolationVitre;

    /**
     * @var surfaceVitre
     *
     * @ORM\Column(name="surfaceVitre", type="float", nullable=true)
     */
    private $surfaceVitre;

    /**
     * @var \SecondOeuvre
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\SecondOeuvre", inversedBy="isolation"))
     * @ORM\JoinColumn(nullable=false,onDelete="CASCADE")
     */
    private $secondOeuvre;

    /**
     * @var float|null
     *
     * @ORM\Column(name="prixTotal", type="float", precision=255, scale=0, nullable=true)
     */
    private $prixTotal;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function getEntityName(){
        return 'Isolation';
    }


    /**
     * Set surfaceMursInterieur.
     *
     * @param float|null $surfaceMursInterieur
     *
     * @return Isolation
     */
    public function setSurfaceMursInterieur($surfaceMursInterieur = null)
    {
        $this->surfaceMursInterieur = $surfaceMursInterieur;

        return $this;
    }

    /**
     * Get surfaceMursInterieur.
     *
     * @return float|null
     */
    public function getSurfaceMursInterieur()
    {
        return $this->surfaceMursInterieur;
    }

    /**
     * Set surfaceMursExterieur.
     *
     * @param float|null $surfaceMursExterieur
     *
     * @return Isolation
     */
    public function setSurfaceMursExterieur($surfaceMursExterieur = null)
    {
        $this->surfaceMursExterieur = $surfaceMursExterieur;

        return $this;
    }

    /**
     * Get surfaceMursExterieur.
     *
     * @return float|null
     */
    public function getSurfaceMursExterieur()
    {
        return $this->surfaceMursExterieur;
    }

    /**
     * Set surfacePlafond.
     *
     * @param float|null $surfacePlafond
     *
     * @return Isolation
     */
    public function setSurfacePlafond($surfacePlafond = null)
    {
        $this->surfacePlafond = $surfacePlafond;

        return $this;
    }

    /**
     * Get surfacePlafond.
     *
     * @return float|null
     */
    public function getSurfacePlafond()
    {
        return $this->surfacePlafond;
    }

    /**
     * Set surfaceSol.
     *
     * @param float|null $surfaceSol
     *
     * @return Isolation
     */
    public function setSurfaceSol($surfaceSol = null)
    {
        $this->surfaceSol = $surfaceSol;

        return $this;
    }

    /**
     * Get surfaceSol.
     *
     * @return float|null
     */
    public function getSurfaceSol()
    {
        return $this->surfaceSol;
    }

    /**
     * Set surfaceVitre.
     *
     * @param float|null $surfaceVitre
     *
     * @return Isolation
     */
    public function setSurfaceVitre($surfaceVitre = null)
    {
        $this->surfaceVitre = $surfaceVitre;

        return $this;
    }

    /**
     * Get surfaceVitre.
     *
     * @return float|null
     */
    public function getSurfaceVitre()
    {
        return $this->surfaceVitre;
    }

    /**
     * Set prixTotal.
     *
     * @param float|null $prixTotal
     *
     * @return Isolation
     */
    public function setPrixTotal($prixTotal = null)
    {
        $this->prixTotal = $prixTotal;

        return $this;
    }

    /**
     * Get prixTotal.
     *
     * @return float|null
     */
    public function getPrixTotal()
    {
        return $this->prixTotal;
    }

    /**
     * Set typeIsolationMur.
     *
     * @param \HomeConstruct\BuildBundle\Entity\TypeIsolationMur $typeIsolationMur
     *
     * @return Isolation
     */
    public function setTypeIsolationMur(\HomeConstruct\BuildBundle\Entity\TypeIsolationMur $typeIsolationMur)
    {
        $this->typeIsolationMur = $typeIsolationMur;

        return $this;
    }

    /**
     * Get typeIsolationMur.
     *
     * @return \HomeConstruct\BuildBundle\Entity\TypeIsolationMur
     */
    public function getTypeIsolationMur()
    {
        return $this->typeIsolationMur;
    }

    /**
     * Set typeIsolationPlafond.
     *
     * @param \HomeConstruct\BuildBundle\Entity\TypeIsolationPlafond $typeIsolationPlafond
     *
     * @return Isolation
     */
    public function setTypeIsolationPlafond(\HomeConstruct\BuildBundle\Entity\TypeIsolationPlafond $typeIsolationPlafond)
    {
        $this->typeIsolationPlafond = $typeIsolationPlafond;

        return $this;
    }

    /**
     * Get typeIsolationPlafond.
     *
     * @return \HomeConstruct\BuildBundle\Entity\TypeIsolationPlafond
     */
    public function getTypeIsolationPlafond()
    {
        return $this->typeIsolationPlafond;
    }

    /**
     * Set typeIsolationPlancher.
     *
     * @param \HomeConstruct\BuildBundle\Entity\TypeIsolationPlancher $typeIsolationPlancher
     *
     * @return Isolation
     */
    public function setTypeIsolationPlancher(\HomeConstruct\BuildBundle\Entity\TypeIsolationPlancher $typeIsolationPlancher)
    {
        $this->typeIsolationPlancher = $typeIsolationPlancher;

        return $this;
    }

    /**
     * Get typeIsolationPlancher.
     *
     * @return \HomeConstruct\BuildBundle\Entity\TypeIsolationPlancher
     */
    public function getTypeIsolationPlancher()
    {
        return $this->typeIsolationPlancher;
    }

    /**
     * Set typeIsolationVitre.
     *
     * @param \HomeConstruct\BuildBundle\Entity\TypeIsolationVitre $typeIsolationVitre
     *
     * @return Isolation
     */
    public function setTypeIsolationVitre(\HomeConstruct\BuildBundle\Entity\TypeIsolationVitre $typeIsolationVitre)
    {
        $this->typeIsolationVitre = $typeIsolationVitre;

        return $this;
    }

    /**
     * Get typeIsolationVitre.
     *
     * @return \HomeConstruct\BuildBundle\Entity\TypeIsolationVitre
     */
    public function getTypeIsolationVitre()
    {
        return $this->typeIsolationVitre;
    }

    /**
     * Set typeIsolation.
     *
     * @param string $typeIsolation
     *
     * @return Isolation
     */
    public function setTypeIsolation($typeIsolation)
    {
        $this->typeIsolation = $typeIsolation;

        return $this;
    }

    /**
     * Get typeIsolation.
     *
     * @return string
     */
    public function getTypeIsolation()
    {
        return $this->typeIsolation;
    }

    /**
     * Set secondOeuvre.
     *
     * @param \HomeConstruct\BuildBundle\Entity\SecondOeuvre $secondOeuvre
     *
     * @return Isolation
     */
    public function setSecondOeuvre(\HomeConstruct\BuildBundle\Entity\SecondOeuvre $secondOeuvre)
    {
        $this->secondOeuvre = $secondOeuvre;

        return $this;
    }

    /**
     * Get secondOeuvre.
     *
     * @return \HomeConstruct\BuildBundle\Entity\SecondOeuvre
     */
    public function getSecondOeuvre()
    {
        return $this->secondOeuvre;
    }

    /**
     * calculPrixTotal
     *
     * @ORM\PrePersist
     * @ORM\PostUpdate
     */
    public function calculPrix(){
        $prixTotal = null;
        if($this->getTypeIsolation() == "thermique"){
            if($this->getSurfaceMursInterieur() != null and
                $this->getSurfaceMursExterieur() != null and
                $this->getSurfacePlafond() != null and
                $this->getSurfaceSol() != null)
            {
                $prixTotal = $this->getSurfaceMursInterieur() * 45 +
                    $this->getSurfaceMursExterieur() * 95 +
                    $this->getSurfacePlafond() * 30 +
                    $this->getSurfaceSol() * 20;
            }
        }else{
            if($this->getSurfaceMursInterieur() != null and
                $this->getSurfaceMursExterieur() != null and
                $this->getSurfacePlafond() != null and
                $this->getSurfaceSol() != null and
                $this->getSurfaceVitre() != null and
                $this->getTypeIsolationMur() != null and
                $this->getTypeIsolationPlafond() != null and
                $this->getTypeIsolationPlancher() != null and
                $this->getTypeIsolationVitre() != null)
            {
                $prixTotal = $this->getTypeIsolationMur()->getPrix() * ($this->getSurfaceMursExterieur() + $this->getSurfaceMursInterieur()) +
                    $this->getTypeIsolationPlafond()->getPrix() * $this->getSurfacePlafond() +
                    $this->getTypeIsolationPlancher()->getPrix() * $this->getSurfaceSol() +
                    $this->getTypeIsolationVitre()->getPrix() * $this->getSurfaceVitre();
            }
        }
        $this->setPrixTotal($prixTotal);
        return $prixTotal;
    }

    /**
     * Calculer le prix du gros oeuvre
     *
     * @ORM\PostPersist
     * @ORM\PostUpdate
     */
    public function appelCalculPrixSO(){
        $secondOeuvre=$this->getSecondOeuvre();
        $secondOeuvre->calculPrix();
    }

    /**
     * Set surfacePlancher.
     *
     * @param float|null $surfacePlancher
     *
     * @return Isolation
     */
    public function setSurfacePlancher($surfacePlancher = null)
    {
        $this->surfacePlancher = $surfacePlancher;

        return $this;
    }

    /**
     * Get surfacePlancher.
     *
     * @return float|null
     */
    public function getSurfacePlancher()
    {
        return $this->surfacePlancher;
    }
}
