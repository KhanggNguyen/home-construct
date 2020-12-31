<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Arbre
 *
 * @ORM\Table(name="home_construct_arbre")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\ArbreRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Arbre
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
     * @var int|null
     *
     * @ORM\Column(name="quantite", type="integer", nullable=true)
     */
    private $quantite;

    /**
     * @var \TypeArbre|null
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\TypeArbre", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $typeArbre;

    /**
     * @var \TailleArbre|null
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\TailleArbre", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $tailleArbre;

    /**
     * @var float|null
     * @ORM\Column(name="tarifArbre", type="float", nullable=true)
     */
    private $tarifArbre;

    /**
     * @var float|null
     *
     * @ORM\Column(name="prixAbattageNettoyage", type="float", precision=255, scale=0, nullable=true)
     */
    private $prixAbattageNettoyage;

    /**
     * @var float|null
     *
     * @ORM\Column(name="prixDessouchage", type="float", precision=255, scale=0, nullable=true)
     */
    private $prixDessouchage;

    /**
     * @var float|null
     *
     * @ORM\Column(name="prixTotal", type="float", precision=255, scale=0, nullable=true)
     */
    private $prixTotal;

    /**
     * @var \ElagageArbre
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\ElagageArbre", cascade={"persist"}, inversedBy="arbres")
     * @ORM\JoinColumn(nullable=false)
     */
    private $elagageArbre;

    /**
     * Constructor
     */
    public function __construct()
    {
    }

    

    /**
     * Set quantite.
     *
     * @param int|null $quantite
     *
     * @return Arbre
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
     * Set typeArbre.
     *
     * @param \HomeConstruct\BuildBundle\Entity\TypeArbre|null $typeArbre
     *
     * @return Arbre
     */
    public function setTypeArbre(\HomeConstruct\BuildBundle\Entity\TypeArbre $typeArbre = null)
    {
        $this->typeArbre = $typeArbre;

        return $this;
    }

    /**
     * Get typeArbre.
     *
     * @return \HomeConstruct\BuildBundle\Entity\TypeArbre|null
     */
    public function getTypeArbre()
    {
        return $this->typeArbre;
    }

    /**
     * Set tailleArbre.
     *
     * @param \HomeConstruct\BuildBundle\Entity\TailleArbre|null $tailleArbre
     *
     * @return Arbre
     */
    public function setTailleArbre(\HomeConstruct\BuildBundle\Entity\TailleArbre $tailleArbre = null)
    {
        $this->tailleArbre = $tailleArbre;

        return $this;
    }

    /**
     * Get tailleArbre.
     *
     * @return \HomeConstruct\BuildBundle\Entity\TailleArbre|null
     */
    public function getTailleArbre()
    {
        return $this->tailleArbre;
    }

    /**
     * Set elagageArbre.
     *
     * @param \HomeConstruct\BuildBundle\Entity\ElagageArbre $elagageArbre
     *
     * @return Arbre
     */
    public function setElagageArbre(\HomeConstruct\BuildBundle\Entity\ElagageArbre $elagageArbre)
    {
        $this->elagageArbre = $elagageArbre;

        return $this;
    }

    /**
     * Get elagageArbre.
     *
     * @return \HomeConstruct\BuildBundle\Entity\ElagageArbre
     */
    public function getElagageArbre()
    {
        return $this->elagageArbre;
    }

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
     * Set prixAbattageNettoyage.
     *
     * @param float|null $prixAbattageNettoyage
     *
     * @return Arbre
     */
    public function setPrixAbattageNettoyage($prixAbattageNettoyage = null)
    {
        $this->prixAbattageNettoyage = $prixAbattageNettoyage;

        return $this;
    }

    /**
     * Get prixAbattageNettoyage.
     *
     * @return float|null
     */
    public function getPrixAbattageNettoyage()
    {
        return $this->prixAbattageNettoyage;
    }

    /**
     * Set prixDessouchage.
     *
     * @param float|null $prixDessouchage
     *
     * @return Arbre
     */
    public function setPrixDessouchage($prixDessouchage = null)
    {
        $this->prixDessouchage = $prixDessouchage;

        return $this;
    }

    /**
     * Get prixDessouchage.
     *
     * @return float|null
     */
    public function getPrixDessouchage()
    {
        return $this->prixDessouchage;
    }

    /**
     * Calculer Prix Arbre
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function calculPrix(){
        $prixTotal = null;
        $prixTotal = $this->getQuantite() * $this->getTypeArbre()->getPrix() * $this->getTailleArbre()->getTaille();
        $prixTotal += $this->getPrixAbattageNettoyage() + $this->getPrixDessouchage() + $this->getTarifArbre();
        $this->setPrixTotal($prixTotal);
        return $prixTotal;
    }

    /**
     * Set prixTotal.
     *
     * @param float|null $prixTotal
     *
     * @return Arbre
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
     * Set tarifArbre.
     *
     * @param float|null $tarifArbre
     *
     * @return Arbre
     */
    public function setTarifArbre($tarifArbre = null)
    {
        $this->tarifArbre = $tarifArbre;

        return $this;
    }

    /**
     * Get tarifArbre.
     *
     * @return float|null
     */
    public function getTarifArbre()
    {
        return $this->tarifArbre;
    }

    public function getEntityName(){
        return 'Arbre';
    }
}
