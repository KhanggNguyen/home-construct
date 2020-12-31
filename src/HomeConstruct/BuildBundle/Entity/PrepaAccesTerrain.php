<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * PrepaAccesTerrain
 *
 * @ORM\Table(name="home_construct_prepa_acces_terain")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\PrepaAccesTerrainRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class PrepaAccesTerrain
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
     * @var \elagageArbres
     *
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\ElagageArbre", cascade={"persist","remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $elagageArbre;
    // prix = prix de coupe (=prix de coupe du type d'arbre/m3 qui est la taille de l'arbre)
    // + tarif forfaitaire abattage + nettoyage (voir tableau marco)
    // + prix dessouchage (en fonction de la hauteur)
    // à faire pour chaque arbre
    // + tarifForfaitaire en fonction du nb d'arbres

    /**
     * @var float
     *
     * @ORM\Column(name="prixAcces", type="float", nullable=true)
     */
    private $prixAcces;
    // 20euros par m² (surface de la maison)

    /**
     * @var \Terrassement
     *
     * @ORM\OneToMany(targetEntity="HomeConstruct\BuildBundle\Entity\Terrassement", mappedBy="prepaAccesTerrain", cascade={"persist","remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $terrassements;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $prixTotal;
    // prixTotal = prixElagage + prixAcces + prixTerrassement

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=false)
     */
    private $surfaceTerrain;

    /**
     * @var \GrosOeuvre
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\GrosOeuvre", inversedBy="prepaAccesTerrain"))
     * @ORM\JoinColumn(nullable=false)
     */
    private $grosOeuvre;

    /**
     * @ORM\ManyToOne(targetEntity="HomeConstruct\UserBundle\Entity\Users", cascade={"persist"}, inversedBy="prepaAccesTerrainCrees")
     * @ORM\JoinColumn(nullable=true)
     */
    private $createur;

    /**
     * @ORM\ManyToOne(targetEntity="HomeConstruct\UserBundle\Entity\Users", cascade={"persist"}, inversedBy="prepaAccesTerrainModifiees")
     * @ORM\JoinColumn(nullable=true)
     */
    private $modifieur;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_modification", type="datetime", nullable=true)
     */
    private $dateModification;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation", type="datetime", nullable=false)
     */
    private $dateCreation;

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
     * Constructor
     */
    public function __construct()
    {
        $this->terrassements = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set prixAcces.
     *
     * @param float|null $prixAcces
     *
     * @return PrepaAccesTerrain
     */
    public function setPrixAcces($prixAcces = null)
    {
        $this->prixAcces = $prixAcces;

        return $this;
    }

    /**
     * Get prixAcces.
     *
     * @return float|null
     */
    public function getPrixAcces()
    {
        return $this->prixAcces;
    }

    /**
     * Set prixTotal.
     *
     * @param float|null $prixTotal
     *
     * @return PrepaAccesTerrain
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
     * Set elagageArbre.
     *
     * @param \HomeConstruct\BuildBundle\Entity\ElagageArbre|null $elagageArbre
     *
     * @return PrepaAccesTerrain
     */
    public function setElagageArbre(\HomeConstruct\BuildBundle\Entity\ElagageArbre $elagageArbre = null)
    {
        $this->elagageArbre = $elagageArbre;

        return $this;
    }

    /**
     * Get elagageArbre.
     *
     * @return \HomeConstruct\BuildBundle\Entity\ElagageArbre|null
     */
    public function getElagageArbre()
    {
        return $this->elagageArbre;
    }

    /**
     * Add terrassement.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Terrassement $terrassement
     *
     * @return PrepaAccesTerrain
     */
    public function addTerrassement(\HomeConstruct\BuildBundle\Entity\Terrassement $terrassement)
    {
        $this->terrassements[] = $terrassement;

        return $this;
    }

    /**
     * Remove terrassement.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Terrassement $terrassement
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeTerrassement(\HomeConstruct\BuildBundle\Entity\Terrassement $terrassement)
    {
        return $this->terrassements->removeElement($terrassement);
    }

    /**
     * Get terrassements.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTerrassements()
    {
        return $this->terrassements;
    }

    /**
     * calculer prix total terrassement
     *
     * @return float
     *
     */
    public function calculPrixTerrassements(){
        $prixTotal = null;
        foreach($this->getTerrassements() as $terrassement){
            $prixTotal += $terrassement->calculPrix();

        }
        return $prixTotal;
    }
    /**
     * Calcule le prix total et le set au prix de la prepa.
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function calculPrix()
    {
        $prixTotalTerrassement = null;
        if(!$this->getTerrassements()->isEmpty()){
            $prixLocation = 1000;
            $prixTotalTerrassement += $prixLocation;
        }
        foreach($this->getTerrassements() as $terrassement){
            //calculer le prix de terrassement
            $terrassement->calculPrix();
            $prixTotalTerrassement += $terrassement->getPrixTotal();
        }
        $prixTotalElagageArbre = $this->getElagageArbre()->calculPrix();
        $prixTotalPrepaAccesTerrain = $prixTotalElagageArbre + $this->getPrixAcces() + $prixTotalTerrassement;
        $this->setPrixTotal($prixTotalPrepaAccesTerrain);
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

    public function getGrosOeuvre(): ?GrosOeuvre
    {
        return $this->grosOeuvre;
    }

    public function setGrosOeuvre(GrosOeuvre $grosOeuvre): self
    {
        $this->grosOeuvre = $grosOeuvre;

        return $this;
    }

    public function getSurfaceTerrain(): ?float
    {
        return $this->surfaceTerrain;
    }

    public function setSurfaceTerrain(float $surfaceTerrain): self
    {
        $this->surfaceTerrain = $surfaceTerrain;

        return $this;
    }

    public function getEntityName(){
        return 'Prepa Acces Terrain';
    }

    /**
     * Ajoute la date actuelle lors d'un ajout (juste avant le persist)
     *
     * @ORM\PrePersist
     */
    public function addThisDate(){
        if(!$this->getDateCreation()){
            $date = date('d/m/Y H:i');
            $date = date_create_from_format('d/m/Y H:i', $date);
            $this->setDateCreation($date);
        }
    }

    /**
     * Ajoute la date actuelle lors d'une modification (juste avant le persist)
     *
     * @ORM\PreUpdate
     */
    public function updateThisDate(){
        $date = date('d/m/Y H:i');
        $date = date_create_from_format('d/m/Y H:i', $date);
        $this->setDateModification($date);
    }

    /**
     * Set dateModification.
     *
     * @param \DateTime|null $dateModification
     *
     * @return PrepaAccesTerrain
     */
    public function setDateModification($dateModification = null)
    {
        $this->dateModification = $dateModification;

        return $this;
    }

    /**
     * Get dateModification.
     *
     * @return \DateTime|null
     */
    public function getDateModification()
    {
        return $this->dateModification;
    }

    /**
     * Set dateCreation.
     *
     * @param \DateTime $dateCreation
     *
     * @return PrepaAccesTerrain
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation.
     *
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set createur.
     *
     * @param \HomeConstruct\UserBundle\Entity\Users|null $createur
     *
     * @return PrepaAccesTerrain
     */
    public function setCreateur(\HomeConstruct\UserBundle\Entity\Users $createur = null)
    {
        $this->createur = $createur;

        return $this;
    }

    /**
     * Get createur.
     *
     * @return \HomeConstruct\UserBundle\Entity\Users|null
     */
    public function getCreateur()
    {
        return $this->createur;
    }

    /**
     * Set modifieur.
     *
     * @param \HomeConstruct\UserBundle\Entity\Users|null $modifieur
     *
     * @return PrepaAccesTerrain
     */
    public function setModifieur(\HomeConstruct\UserBundle\Entity\Users $modifieur = null)
    {
        $this->modifieur = $modifieur;

        return $this;
    }

    /**
     * Get modifieur.
     *
     * @return \HomeConstruct\UserBundle\Entity\Users|null
     */
    public function getModifieur()
    {
        return $this->modifieur;
    }
}
