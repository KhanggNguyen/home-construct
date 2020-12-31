<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Excavation
 *
 * @ORM\Table(name="home_construct_excavation")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\ExcavationRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 */
class Excavation
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
     * @var float|null
     *
     * @ORM\Column(name="nbMetresMurPeriph", type="float", nullable=false)
     */
    private $nbMetresMurPeriph;

    /**
     * @var float|null
     *
     * @ORM\Column(name="nbMetresMurRefont", type="float", nullable=false)
     */
    private $nbMetresMurRefont;

    /**
     * @var float|null
     *
     * @ORM\Column(name="largeurFouille", type="float", nullable=false)
     */
    private $largeurFouille;

    /**
     * @var float|null
     *
     * @ORM\Column(name="profondeurFouille", type="float", nullable=false)
     */
    private $profondeurFouille;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="fouilleGarage", type="boolean", nullable=false)
     */
    private $fouilleGarage;

    /**
     * @var float|null
     *
     * @ORM\Column(name="prixTotal", type="float", nullable=true)
     */
    private $prixTotal;

    /**
     * @var \TypeTerrassement
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\TypeTerrassement", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $typeTerrassement;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="HomeConstruct\BuildBundle\Entity\MaterielExcavation", inversedBy="excavations")
     * @ORM\JoinTable(name="home_construct_join_excavation_materiel",
     *   joinColumns={@ORM\JoinColumn(name="excavation_id", referencedColumnName="id",nullable=true)},
     *   inverseJoinColumns={@ORM\JoinColumn(name="materiel_id", referencedColumnName="id",nullable=true)}
     * )
     */
    private $materiels;

    /**
     * @ORM\ManyToOne(targetEntity="HomeConstruct\UserBundle\Entity\Users", cascade={"persist"}, inversedBy="excavationsCrees")
     * @ORM\JoinColumn(nullable=true)
     */
    private $createur;

    /**
     * @ORM\ManyToOne(targetEntity="HomeConstruct\UserBundle\Entity\Users", cascade={"persist"}, inversedBy="excavationsModifiees")
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
     * @var \GrosOeuvre
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\GrosOeuvre", inversedBy="excavation"))
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
     * Set nbMetresMurPeriph.
     *
     * @param float|null $nbMetresMurPeriph
     *
     * @return Excavation
     */
    public function setNbMetresMurPeriph($nbMetresMurPeriph = null)
    {
        $this->nbMetresMurPeriph = $nbMetresMurPeriph;

        return $this;
    }

    /**
     * Get nbMetresMurPeriph.
     *
     * @return float|null
     */
    public function getNbMetresMurPeriph()
    {
        return $this->nbMetresMurPeriph;
    }

    /**
     * Set nbMetresMurRefont.
     *
     * @param float|null $nbMetresMurRefont
     *
     * @return Excavation
     */
    public function setNbMetresMurRefont($nbMetresMurRefont = null)
    {
        $this->nbMetresMurRefont = $nbMetresMurRefont;

        return $this;
    }

    /**
     * Get nbMetresMurRefont.
     *
     * @return float|null
     */
    public function getNbMetresMurRefont()
    {
        return $this->nbMetresMurRefont;
    }

    /**
     * Set profondeurFouille.
     *
     * @param float|null $profondeurFouille
     *
     * @return Excavation
     */
    public function setProfondeurFouille($profondeurFouille = null)
    {
        $this->profondeurFouille = $profondeurFouille;

        return $this;
    }

    /**
     * Get profondeurFouille.
     *
     * @return float|null
     */
    public function getProfondeurFouille()
    {
        return $this->profondeurFouille;
    }

    /**
     * Set fouilleGarage.
     *
     * @param bool|null $fouilleGarage
     *
     * @return Excavation
     */
    public function setFouilleGarage($fouilleGarage = null)
    {
        $this->fouilleGarage = $fouilleGarage;

        return $this;
    }

    /**
     * Get fouilleGarage.
     *
     * @return bool|null
     */
    public function getFouilleGarage()
    {
        return $this->fouilleGarage;
    }

    /**
     * Set prixTotal.
     *
     * @param float|null $prixTotal
     *
     * @return Excavation
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
     * Constructor
     */
    public function __construct()
    {
        $this->materiels = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set typeTerrassement.
     *
     * @param \HomeConstruct\BuildBundle\Entity\TypeTerrassement $typeTerrassement
     *
     * @return Excavation
     */
    public function setTypeTerrassement(\HomeConstruct\BuildBundle\Entity\TypeTerrassement $typeTerrassement)
    {
        $this->typeTerrassement = $typeTerrassement;

        return $this;
    }

    /**
     * Get typeTerrassement.
     *
     * @return \HomeConstruct\BuildBundle\Entity\TypeTerrassement
     */
    public function getTypeTerrassement()
    {
        return $this->typeTerrassement;
    }

    /**
     * Add materiel.
     *
     * @param \HomeConstruct\BuildBundle\Entity\MaterielExcavation $materiel
     *
     * @return Excavation
     */
    public function addMateriel(\HomeConstruct\BuildBundle\Entity\MaterielExcavation $materiel)
    {
        $this->materiels[] = $materiel;

        return $this;
    }

    /**
     * Remove materiel.
     *
     * @param \HomeConstruct\BuildBundle\Entity\MaterielExcavation $materiel
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeMateriel(\HomeConstruct\BuildBundle\Entity\MaterielExcavation $materiel)
    {
        return $this->materiels->removeElement($materiel);
    }

    /**
     * Get materiels.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMateriels()
    {
        return $this->materiels;
    }

    /**
     * Set largeurFouille.
     *
     * @param float|null $largeurFouille
     *
     * @return Excavation
     */
    public function setLargeurFouille($largeurFouille = null)
    {
        $this->largeurFouille = $largeurFouille;

        return $this;
    }

    /**
     * Get largeurFouille.
     *
     * @return float|null
     */
    public function getLargeurFouille()
    {
        return $this->largeurFouille;
    }

    /**
     * Calculer le prix d'excavation
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function calculPrix(){
        $prixTotal = null;
        /*foreach($this->getMateriels() as $materiel){
            $prixTotal += $materiel->getPrix();
        }*/
        if($this->getTypeTerrassement() and $this->getNbMetresMurRefont() and $this->getNbMetresMurPeriph() and $this->getLargeurFouille() and $this->getProfondeurFouille()){
            $prixTotal += ($this->getNbMetresMurPeriph() + $this->getNbMetresMurRefont()) * $this->getLargeurFouille() * $this->getProfondeurFouille() * $this->getTypeTerrassement()->getPrix();
            $this->setPrixTotal($prixTotal);
        }else{
            $this->setPrixTotal($prixTotal);
        }
        return $prixTotal;
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


    public function getEntityName(){
        return 'Excavation';
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


    /**
     * Set dateModification.
     *
     * @param \DateTime|null $dateModification
     *
     * @return Excavation
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
     * @return Excavation
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Ajoute la date actuelle lors d'un ajout (juste avant le persist)
     *
     * @ORM\PrePersist
     */
    public function addThisDate(){
        $date = date('d/m/Y H:i');
        $date = date_create_from_format('d/m/Y H:i', $date);
        $this->setDateCreation($date);
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
     * @return Excavation
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
     * @return Excavation
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
