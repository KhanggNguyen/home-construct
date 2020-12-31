<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManagerInterface;

/**
 * RevetementSol
 *
 * @ORM\Table(name="home_construct_revetement_sol")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\RevetementSolRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class RevetementSol
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
     * @ORM\Column(type="integer", precision=255, scale=0, nullable=true)
     */
    private $quantiteM2;

    /**
     * @var \TypeRevetementSol
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\TypeRevetementSol", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\Column(type="float")
     */
    private $prix;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\SecondOeuvre", cascade={"persist"}, inversedBy="revetementSol"))
     * @ORM\JoinColumn(nullable=false)
     */
    private $secondOeuvre;

    /**
     * @var \Piece
     *
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\Piece", cascade={"persist"}, mappedBy="revetementSol")
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
     * @param \HomeConstruct\BuildBundle\Entity\TypeRevetementSol $type
     *
     * @return RevetementSol
     */
    public function setType(\HomeConstruct\BuildBundle\Entity\TypeRevetementSol $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return \HomeConstruct\BuildBundle\Entity\TypeRevetementSol
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
     * @return RevetementSol
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
     * Set quantiteM2.
     *
     * @param int $quantiteM2
     *
     * @return RevetementSol
     */
    public function setQuantiteM2($quantiteM2)
    {
        $this->quantiteM2 = $quantiteM2;

        return $this;
    }

    /**
     * Get quantiteM2.
     *
     * @return int
     */
    public function getQuantiteM2()
    {
        return $this->quantiteM2;
    }

    public function getEntityName(){
        return 'RevetementSol';
    }

    /**
     * Calculer le prix de l'entité
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function calculPrix(){
        $prix=($this->getType()->getPrix()*$this->getQuantiteM2());
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

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Set piece.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Piece|null $piece
     *
     * @return RevetementSol
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
