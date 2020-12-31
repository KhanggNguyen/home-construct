<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Excavation
 *
 * @ORM\Table(name="home_construct_plomberie")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\PlomberieRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 */
class Plomberie
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
     * @var float|null
     *
     * @ORM\Column(name="m2", type="float", nullable=true)
     */
    private $m2;

    /**
     * @var float|null
     *
     * @ORM\Column(name="prixTotal", type="float", nullable=false)
     */
    private $prixTotal;

    /**
     * @var float|null
     *
     * @ORM\Column(name="quantite", type="float", nullable=true)
     */
    private $quantite;

    /**
     * @var \TypePlomberie
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\TypePlomberie", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $typePlomberie;

    /**
     * @var \SecondOeuvre
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\SecondOeuvre", inversedBy="plomberies"))
     * @ORM\JoinColumn(nullable=false)
     */
    private $secondOeuvre;


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
     * Set m2.
     *
     * @param float|null $m2
     *
     * @return Plomberie
     */
    public function setM2($m2 = null)
    {
        $this->m2 = $m2;

        return $this;
    }

    /**
     * Get m2.
     *
     * @return float|null
     */
    public function getM2()
    {
        return $this->m2;
    }

    /**
     * Set prixTotal.
     *
     * @param float $prixTotal
     *
     * @return Plomberie
     */
    public function setPrixTotal($prixTotal)
    {
        $this->prixTotal = $prixTotal;

        return $this;
    }

    /**
     * Get prixTotal.
     *
     * @return float
     */
    public function getPrixTotal()
    {
        return $this->prixTotal;
    }

    /**
     * Set typePlomberie.
     *
     * @param \HomeConstruct\BuildBundle\Entity\TypePlomberie $typePlomberie
     *
     * @return Plomberie
     */
    public function setTypePlomberie(\HomeConstruct\BuildBundle\Entity\TypePlomberie $typePlomberie)
    {
        $this->typePlomberie = $typePlomberie;

        return $this;
    }

    /**
     * Get typePlomberie.
     *
     * @return \HomeConstruct\BuildBundle\Entity\TypePlomberie
     */
    public function getTypePlomberie()
    {
        return $this->typePlomberie;
    }

    /**
     * Set secondOeuvre.
     *
     * @param \HomeConstruct\BuildBundle\Entity\SecondOeuvre $secondOeuvre
     *
     * @return Plomberie
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

    public function getEntityName(){
        return 'Plomberie';
    }

    /**
     * Set quantite.
     *
     * @param float|null $quantite
     *
     * @return Plomberie
     */
    public function setQuantite($quantite = null)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite.
     *
     * @return float|null
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * Calculer le prix de plomberie
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function calculPrix(){
        $prixTotal = null;
        if($this->getTypePlomberie()->getParM2()){
            if($this->getM2() != null && $this->getTypePlomberie() != null){
                $prixTotal = ($this->getM2() * $this->getTypePlomberie()->getPrix()) + ($this->getM2() * $this->getTypePlomberie()->getMainOeuvre());
                $this->setPrixTotal($prixTotal);
            }
        }else{
            if($this->getQuantite() != null && $this->getTypePlomberie() != null){
                $prixTotal = $this->getQuantite() * ($this->getTypePlomberie()->getPrix() + $this->getTypePlomberie()->getMainOeuvre());
                $this->setPrixTotal($prixTotal);
            }
        }
        return $prixTotal;
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
}
