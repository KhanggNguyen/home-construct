<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Domotique
 *
 * @ORM\Table(name="home_construct_domotique")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\DomotiqueRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Domotique
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
     * @var \TypeDomotique
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\TypeDomotique", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;


    /**
     * @var integer|null
     *
     * @ORM\Column(name="quantite", type="integer", precision=255, scale=0, nullable=false)
     */
    private $quantite;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float", length=64, nullable=false)
     */
    private $prix;

    /**
     * @var \SecondOeuvre
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\SecondOeuvre", cascade={"persist"}, inversedBy="domotiques"))
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
     * Set quantite.
     *
     * @param int $quantite
     *
     * @return Domotique
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
        return 'Domotique';
    }

    public function getType(): ?TypeDomotique
    {
        return $this->type;
    }

    public function setType(?TypeDomotique $type): self
    {
        $this->type = $type;

        return $this;
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
     * Calculer le prix de l'entité
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function calculPrix(): ?float
    {
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
}
