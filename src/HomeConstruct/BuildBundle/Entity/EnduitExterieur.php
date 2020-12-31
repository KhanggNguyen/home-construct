<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EnduitExterieur
 *
 * @ORM\Table(name="home_construct_enduit_exterieur")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\EnduitExterieurRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class EnduitExterieur
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
     *
     * @var typeEnduit
     *
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\TypeEnduitExterieur", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $typeEnduit;

    /**
     * @var float|null
     *
     * @ORM\Column(name="surfaceMur", type="float", precision=255, scale=0, nullable=false)
     */
    private $surfaceMur;

    /**
     *
     * @var float|null
     *
     * @ORM\Column(name="prixTotal", type="float", nullable=true)
     */
    private $prixTotal;

    /**
     * @var \SecondOeuvre
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\SecondOeuvre", inversedBy="enduitExterieur"))
     * @ORM\JoinColumn(nullable=false,onDelete="CASCADE")
     */
    private $secondOeuvre;

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
        return 'Enduit Exterieur';
    }

    /**
     * Set surfaceMur.
     *
     * @param float|null $surfaceMur
     *
     * @return EnduitExterieur
     */
    public function setSurfaceMur($surfaceMur = null)
    {
        $this->surfaceMur = $surfaceMur;

        return $this;
    }

    /**
     * Get surfaceMur.
     *
     * @return float|null
     */
    public function getSurfaceMur()
    {
        return $this->surfaceMur;
    }

    /**
     * Set prixTotal.
     *
     * @param float|null $prixTotal
     *
     * @return EnduitExterieur
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
     * Set typeEnduit.
     *
     * @param \HomeConstruct\BuildBundle\Entity\TypeEnduit|null $typeEnduit
     *
     * @return EnduitExterieur
     */
    public function setTypeEnduit(\HomeConstruct\BuildBundle\Entity\TypeEnduitExterieur $typeEnduit = null)
    {
        $this->typeEnduit = $typeEnduit;

        return $this;
    }

    /**
     * Get typeEnduit.
     *
     * @return \HomeConstruct\BuildBundle\Entity\TypeEnduitExterieur|null
     */
    public function getTypeEnduit()
    {
        return $this->typeEnduit;
    }

    /**
     * prix Total de l'enduit extérieur
     *
     * @ORM\PrePersist
     * @ORM\PostUpdate
     *
     * @return prixTotal
     */
    public function calculPrix(){
        $prixTotal = null;
        $prixTotal = $this->getSurfaceMur() * $this->getTypeEnduit()->getPrix();
        $this->setPrixTotal($prixTotal);
        return $prixTotal;
    }

    /**
     * Set secondOeuvre.
     *
     * @param \HomeConstruct\BuildBundle\Entity\SecondOeuvre $secondOeuvre
     *
     * @return EnduitExterieur
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
}
