<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Ventilation
 *
 * @ORM\Table(name="home_construct_ventilation")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\VentilationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Ventilation
{
    protected $em;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var \TypeVentilation
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\TypeVentilation", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\Column(type="float")
     */
    private $prix;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantite;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\SecondOeuvre", cascade={"persist"}, inversedBy="ventilations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $secondOeuvre;

    /**
     * @var \Piece
     *
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\Piece", cascade={"persist"},mappedBy="ventilation"))
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

    public function getId(): ?int
    {
        return $this->id;
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

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;
        return $this;
    }

    /**
     * Calculer le prix de la ventilation et le set à l'entité
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function calculPrix(){
        if($this->getType() and $this->getQuantite()){
            $prix=($this->getType()->getPrix()*$this->getQuantite());
        }else{
            $prix=0;
        }

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

    public function getEntityName(){
        return 'Ventilation';
    }

    public function getSecondOeuvre(): ?SecondOeuvre
    {
        return $this->secondOeuvre;
    }

    public function setSecondOeuvre(?SecondOeuvre $secondOeuvre): self
    {
        $this->secondOeuvre = $secondOeuvre;

        return $this;
    }

    public function getType(): ?TypeVentilation
    {
        return $this->type;
    }

    public function setType(?TypeVentilation $type): self
    {
        $this->type = $type;

        return $this;
    }



    /**
     * Set piece.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Piece|null $piece
     *
     * @return Ventilation
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
