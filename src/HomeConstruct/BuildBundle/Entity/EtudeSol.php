<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManagerInterface;

/**
 * EtudeSol
 *
 * @ORM\Table(name="home_construct_etude_sol")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\EtudeSolRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class EtudeSol
{
    protected $em;
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \TypeSol
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\TypeSol", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $profondeurChoisie;

    /**
     * @var float
     *
     * @ORM\Column(name="prixForfait", type="float")
     */
    private $prixForfait;
    // surface en m² (de la maison)*10
    // si sousSol + 240euros (si sousSol true dans informationBase)
    // pente entre 10% et 20% = + 360 euros

    /**
     * @var \GrosOeuvre
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\GrosOeuvre", inversedBy="etudeSol"))
     * @ORM\JoinColumn(nullable=false)
     */
    private $grosOeuvre;

    /**
     * @ORM\ManyToOne(targetEntity="HomeConstruct\UserBundle\Entity\Users", cascade={"persist"}, inversedBy="etudesSolsCrees")
     * @ORM\JoinColumn(nullable=true)
     */
    private $createur;

    /**
     * @ORM\ManyToOne(targetEntity="HomeConstruct\UserBundle\Entity\Users", cascade={"persist"}, inversedBy="etudesSolsModifiees")
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


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function setEm(EntityManagerInterface $entityManager):self
    {
        $this->em = $entityManager;
        return $this;
    }

    public function getEm(): ?EntityManagerInterface
    {
        return $this->em;
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
     * Set type.
     *
     * @param string $type
     *
     * @return EtudeSol
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set prixForfait.
     *
     * @param float $prixForfait
     *
     * @return EtudeSol
     */
    public function setPrixForfait($prixForfait)
    {
        $this->prixForfait = $prixForfait;

        return $this;
    }

    /**
     * Get prixForfait.
     *
     * @return float
     */
    public function getPrixForfait()
    {
        return $this->prixForfait;
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

    /**
     * Calcule le prix total et le set au prix de l'étude du sol.
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function calculPrix()
    {
        $surface=$this->getGrosOeuvre()->getInformationBase()->getSurfaceTotale();
        $prix=$surface*10;
        $sousSol=$this->getGrosOeuvre()->getInformationBase()->getSousSol();
        if($sousSol==true){
            $prix+=240;
        }
        $this->setPrixForfait($prix);
        return $prix;
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

    public function getProfondeurChoisie(): ?float
    {
        return $this->profondeurChoisie;
    }

    public function setProfondeurChoisie(float $profondeurChoisie): self
    {
        $this->profondeurChoisie = $profondeurChoisie;

        return $this;
    }

    public function getEntityName(){
        return 'Etude Sol';
    }


    /**
     * Set dateModification.
     *
     * @param \DateTime|null $dateModification
     *
     * @return EtudeSol
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
     * @return EtudeSol
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
     * @return EtudeSol
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
     * Set modifieur.
     *
     * @param \HomeConstruct\UserBundle\Entity\Users|null $modifieur
     *
     * @return EtudeSol
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
