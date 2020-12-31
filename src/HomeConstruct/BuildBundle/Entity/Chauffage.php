<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Chauffage
 *
 * @ORM\Table(name="home_construct_chauffage")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\ChauffageRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Chauffage
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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\SecondOeuvre", cascade={"persist"}, inversedBy="chauffage")
     * @ORM\JoinColumn(nullable=false)
     */
    private $secondOeuvre;

    /**
     * @var integer|null
     *
     * @ORM\Column(name="quantite", type="integer", precision=255, scale=0, nullable=false)
     */
    private $quantite;

    /**
     * @var float
     *
     * @ORM\Column(type="float", precision=255, scale=0, nullable=true)
     */
    private $prix;

    /**
     * @var \TypeChauffage
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\TypeChauffage", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @var \Piece
     *
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\Piece", cascade={"persist"}, mappedBy="chauffage")
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
     * @param \HomeConstruct\BuildBundle\Entity\TypeChauffage $type
     *
     * @return Chauffage
     */
    public function setType(\HomeConstruct\BuildBundle\Entity\TypeChauffage $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return \HomeConstruct\BuildBundle\Entity\TypeChauffage
     */
    public function getType()
    {
        return $this->type;
    }


    public function getEntityName(){
        return 'Chauffage';
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

    /**
     * Set quantite.
     *
     * @param int $quantite
     *
     * @return Chauffage
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

    /**
     * Set prix.
     *
     * @param float|null $prix
     *
     * @return Chauffage
     */
    public function setPrix($prix = null)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix.
     *
     * @return float|null
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set secondOeuvre.
     *
     * @param \HomeConstruct\BuildBundle\Entity\SecondOeuvre|null $secondOeuvre
     *
     * @return Chauffage
     */
    public function setSecondOeuvre(\HomeConstruct\BuildBundle\Entity\SecondOeuvre $secondOeuvre = null)
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
     * Set piece.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Piece|null $piece
     *
     * @return Chauffage
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
