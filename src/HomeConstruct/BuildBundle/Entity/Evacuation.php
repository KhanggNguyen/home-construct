<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Evacuation
 *
 * @ORM\Table(name="home_construct_evacuation")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\EvacuationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Evacuation
{
    protected $em;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var \TypeEvacuation
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\TypeEvacuation", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\Column(type="float")
     */
    private $prix;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", length=64, nullable=false)
     */
    private $quantite;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\SecondOeuvre", cascade={"persist"}, inversedBy="evacuations"))
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
     * Set prix.
     *
     * @param float $prix
     *
     * @return Evacuation
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix.
     *
     * @return float
     */
    public function getPrix()
    {
        return $this->prix;
    }

    public function getEntityName(){
        return 'Evacuation';
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
     * Calculer le prix de l'evacuation et le set à l'entité
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

    public function getType(): ?TypeEvacuation
    {
        return $this->type;
    }

    public function setType(?TypeEvacuation $type): self
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
}
