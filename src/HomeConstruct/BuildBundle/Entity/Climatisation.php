<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Climatisation
 *
 * @ORM\Table(name="home_construct_climatisation")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\ClimatisationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Climatisation
{
    protected $em;
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer|null
     *
     * @ORM\Column(name="quantite", type="integer", precision=255, scale=0, nullable=true)
     */
    private $quantite;

    /**
     * @var \TypeClimatisation
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\TypeClimatisation", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @var float
     *
     * @ORM\Column(type="float", precision=255, scale=0, nullable=true)
     */
    private $prix;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\SecondOeuvre", cascade={"persist"}, inversedBy="climatisation"))
     * @ORM\JoinColumn(nullable=false)
     */
    private $secondOeuvre;

    /**
     * @var \Piece
     *
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\Piece", cascade={"persist"},mappedBy="climatisation")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $piece;


    public function __construct(EntityManagerInterface $entityManager=null)
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
     * @param \HomeConstruct\BuildBundle\Entity\TypeClimatisation $type
     *
     * @return Climatisation
     */
    public function setType(\HomeConstruct\BuildBundle\Entity\TypeClimatisation $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return \HomeConstruct\BuildBundle\Entity\TypeClimatisation
     */
    public function getType()
    {
        return $this->type;
    }


    /**
     * Set secondOeuvre.
     *
     * @param \HomeConstruct\BuildBundle\Entity\SecondOeuvre $secondOeuvre
     *
     * @return Climatisation
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
     * Set quantite.
     *
     * @param int $quantite
     *
     * @return Climatisation
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite.
     *
     * @return int
     */
    public function getQuantite()
    {
        return $this->quantite;
    }


    public function getEntityName(){
        return 'Climatisation';
    }

    /**
     * Calculer le prix de l'entité
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function calculPrix(){
        $prix=$this->getType()->getPrix()*$this->getQuantite();
        $this->setPrix($prix);
        return $prix;
    }

    /**
     * Calculer le prix du second oeuvre et du projet
     *
     * @ORM\PostPersist
     * @ORM\PostUpdate
     */
    public function calculPrixSoEtProjet(){
        $em=$this->em;
        $secondOeuvre=$this->getSecondOeuvre();
        $secondOeuvre->calculPrix();
        $em->persist($secondOeuvre);
        $em->flush();
        $projet=$this->getSecondOeuvre()->getProjet();
        $projet->calculPrix();
        $em->persist($projet);
        $em->flush();
    }

    public function __toString(){
        // to show the name of the Category in the select
        return $this->nom;
        // to show the id of the Category in the select
        // return $this->id;
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
     * Set piece.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Piece|null $piece
     *
     * @return Climatisation
     */
    public function setPiece(\HomeConstruct\BuildBundle\Entity\Piece $piece = null)
    {
        $this->piece = $piece;

        return $this;
    }

    /**
     * Get piece.
     *
     * @return \HomeConstruct\BuildBundle\Entity\Piece|null
     */
    public function getPiece()
    {
        return $this->piece;
    }
}
