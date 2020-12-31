<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use HomeConstruct\UserBundle\Entity\Users;

/**
 * Projet
 *
 * @ORM\Table(name="home_construct_projet")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\ProjetRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Projet
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
     * @var string|null
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    private $nom;

    /**
     * @var \GrosOeuvre
     *
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\GrosOeuvre", cascade={"persist","remove"}, inversedBy="projet")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $grosOeuvre;

    /**
     * @var \SecondOeuvre
     *
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\SecondOeuvre", cascade={"persist","remove"}, inversedBy="projet")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $secondOeuvre;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="HomeConstruct\UserBundle\Entity\Users", mappedBy="projets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity="HomeConstruct\UserBundle\Entity\Users", cascade={"persist"}, inversedBy="projetsCrees")
     * @ORM\JoinColumn(nullable=true)
     */
    private $createur;

    /**
    * @var \DateTime
    *
    * @ORM\Column(name="date_creation", type="datetime", nullable=false)
    */
    private $dateCreation;

    /**
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\EtatProjet", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $etat;

    /**
     * @var float
     *
     * @ORM\Column(type="float", precision=255, scale=0, nullable=true)
     */
    private $prix;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nom.
     *
     * @param string|null $nom
     *
     * @return Projet
     */
    public function setNom($nom = null)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom.
     *
     * @return string|null
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set grosOeuvre.
     *
     * @param \HomeConstruct\BuildBundle\Entity\GrosOeuvre|null $grosOeuvre
     *
     * @return Projet
     */
    public function setGrosOeuvre(GrosOeuvre $grosOeuvre = null)
    {
        $this->grosOeuvre = $grosOeuvre;

        return $this;
    }

    /**
     * Get grosOeuvre.
     *
     * @return \HomeConstruct\BuildBundle\Entity\GrosOeuvre|null
     */
    public function getGrosOeuvre()
    {
        return $this->grosOeuvre;
    }

    /**
     * Set secondOeuvre.
     *
     * @param \HomeConstruct\BuildBundle\Entity\SecondOeuvre|null $secondOeuvre
     *
     * @return Projet
     */
    public function setSecondOeuvre(SecondOeuvre $secondOeuvre = null)
    {
        $this->secondOeuvre = $secondOeuvre;

        return $this;
    }

    /**
     * Get secondOeuvre.
     *
     * @return \HomeConstruct\BuildBundle\Entity\SecondOeuvre|null
     */
    public function getSecondOeuvre()
    {
        return $this->secondOeuvre;
    }

    /**
     * Add user.
     *
     * @param \HomeConstruct\UserBundle\Entity\Users $user
     *
     * @return Projet
     */
    public function addUser(Users $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user.
     *
     * @param \HomeConstruct\UserBundle\Entity\Users $user
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeUser(Users $user)
    {
        return $this->users->removeElement($user);
    }

    /**
     * Get users.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set createur.
     *
     * @param \HomeConstruct\UserBundle\Entity\Users $createur
     *
     * @return Projet
     */
    public function setCreateur(\HomeConstruct\UserBundle\Entity\Users $createur=null)
    {
        $this->createur = $createur;

        return $this;
    }

    /**
     * Get createur.
     *
     * @return \HomeConstruct\UserBundle\Entity\Users
     */
    public function getCreateur()
    {
        return $this->createur;
    }

    /**
     * Set dateCreation.
     *
     * @param \DateTime $dateCreation
     *
     * @return Projet
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

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Calcule le prix total et le set au prix du gros oeuvre.
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     *
     */
    public function calculPrix()
    {
        if($this->getGrosOeuvre()!=null){
            if($this->getGrosOeuvre()->getId()){
                $prixGrosOeuvre=$this->getGrosOeuvre()->getPrix();
            }else{
                $prixGrosOeuvre=null;
            }
        }else{
            $prixGrosOeuvre=null;
        }
        if($this->getSecondOeuvre()!=null){
            if($this->getSecondOeuvre()->getId()) {
                $prixSecondOeuvre = $this->getSecondOeuvre()->getPrix();
            }else{
                $prixSecondOeuvre=null;
            }
        }else{
            $prixSecondOeuvre=null;
        }
        $this->setPrix($prixGrosOeuvre+$prixSecondOeuvre);
    }

    public function getEtat(): ?EtatProjet
    {
        return $this->etat;
    }

    public function setEtat(?EtatProjet $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getEntityName(){
        return 'Projet';
    }
}
