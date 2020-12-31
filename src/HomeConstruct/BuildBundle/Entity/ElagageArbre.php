<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ElagageArbre
 *
 * @ORM\Table(name="home_construct_elagage_arbre")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\ElagageArbreRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ElagageArbre
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
     * @var float|null
     *
     * @ORM\Column(name="prixTotal", type="float", nullable=true)
     */
    private $prixTotal;

    /**
     * @var float
     *
     * @ORM\Column(name="prixTarifArbre", type="float", nullable=true)
     */
    private $prixTarifArbre;


    /**
     * @var arbre
     * @ORM\OneToMany(targetEntity="HomeConstruct\BuildBundle\Entity\Arbre", mappedBy="elagageArbre", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $arbres;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->arbres = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set prixTotal.
     *
     * @param float|null $prixTotal
     *
     * @return ElagageArbre
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
     * Add arbre.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Arbre $arbre
     *
     * @return ElagageArbre
     */
    public function addArbre(\HomeConstruct\BuildBundle\Entity\Arbre $arbre)
    {
        $this->arbres[] = $arbre;

        return $this;
    }

    /**
     * Remove arbre.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Arbre $arbre
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeArbre(\HomeConstruct\BuildBundle\Entity\Arbre $arbre)
    {
        return $this->arbres->removeElement($arbre);
    }

    /**
     * Get arbres.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArbres()
    {
        return $this->arbres;
    }

    /**
     * Set prixTarifArbre.
     *
     * @param float $prixTarifArbre
     *
     * @return ElagageArbre
     */
    public function setPrixTarifArbre($prixTarifArbre)
    {
        $this->prixTarifArbre = $prixTarifArbre;

        return $this;
    }

    /**
     * Get prixTarifArbre.
     *
     * @return float
     */
    public function getPrixTarifArbre()
    {
        return $this->prixTarifArbre;
    }

    /**
     * Calculer prix tarif total des arbres
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     *
     */
    public function calculTarifArbres(){
        $tarif = 0;
        foreach($this->getArbres() as $arbre){
            $tarif += $arbre->getTarifArbre();
        }
        $this->setPrixTarifArbre($tarif);
        return $tarif;
    }

    /**
     * Calculer Prix Total des Arbres
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function calculPrix(){
        $prixElagageArbre = null;
        foreach($this->getArbres() as $arbre){
            $arbre->calculPrix();
            $prixElagageArbre += $arbre->calculPrix();
        }
        $this->setPrixTotal(($prixElagageArbre));
        return $this->prixTotal;
    }

    public function getEntityName(){
        return 'Elagage Arbre';
    }
}
