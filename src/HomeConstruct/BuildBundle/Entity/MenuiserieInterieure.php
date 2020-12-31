<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManagerInterface;

/**
 * MenuiserieInterieure
 *
 * @ORM\Table(name="home_construct_menuiserie_interieure")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\MenuiserieInterieureRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class MenuiserieInterieure
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
     * @var \DimensionMenuiserieInterieure
     *
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\DimensionMenuiserieInterieure", cascade={"persist","remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $dimension;

    /**
     * @var \TypeMenuiserieInterieure
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\TypeMenuiserieInterieure", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @var \MateriauxMenuiserieInterieure
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\MateriauxMenuiserieInterieure", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $materiaux;

    /**
     * @var int|null
     *
     * @ORM\Column(name="quantite", type="integer", length=64, nullable=false)
     */
    private $quantite;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float", length=64, nullable=false)
     */
    private $prix;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\SecondOeuvre", cascade={"persist"}, inversedBy="menuiseriesInterieures"))
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
     * @return MenuiserieInterieure
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
     * @param float $prix
     *
     * @return MenuiserieInterieure
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

    /**
     * Set dimension.
     *
     * @param \HomeConstruct\BuildBundle\Entity\DimensionMenuiserieInterieure $dimension
     *
     * @return MenuiserieInterieure
     */
    public function setDimension(\HomeConstruct\BuildBundle\Entity\DimensionMenuiserieInterieure $dimension)
    {
        $this->dimension = $dimension;

        return $this;
    }

    /**
     * Get dimension.
     *
     * @return \HomeConstruct\BuildBundle\Entity\DimensionMenuiserieInterieure
     */
    public function getDimension()
    {
        return $this->dimension;
    }

    /**
     * Set type.
     *
     * @param \HomeConstruct\BuildBundle\Entity\TypeMenuiserieInterieure $type
     *
     * @return MenuiserieInterieure
     */
    public function setType(\HomeConstruct\BuildBundle\Entity\TypeMenuiserieInterieure $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return \HomeConstruct\BuildBundle\Entity\TypeMenuiserieInterieure
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set materiaux.
     *
     * @param \HomeConstruct\BuildBundle\Entity\MateriauxMenuiserieInterieure $materiaux
     *
     * @return MenuiserieInterieure
     */
    public function setMateriaux(\HomeConstruct\BuildBundle\Entity\MateriauxMenuiserieInterieure $materiaux)
    {
        $this->materiaux = $materiaux;

        return $this;
    }

    /**
     * Get materiaux.
     *
     * @return \HomeConstruct\BuildBundle\Entity\MateriauxMenuiserieInterieure
     */
    public function getMateriaux()
    {
        return $this->materiaux;
    }

    /**
     * Set secondOeuvre.
     *
     * @param \HomeConstruct\BuildBundle\Entity\SecondOeuvre $secondOeuvre
     *
     * @return MenuiserieInterieure
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
        return 'Menuiserie Interieure';
    }

    /**
     * Calculer le prix de l'entité
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function calculPrix(){
        $prixTotal = null;
        if($this->getType()){
            $type = $this->getType()->getNom();
        }else{
            $this->setPrix($prixTotal);
            return null;
        }
        if($this->getMateriaux()){
            $materiel = $this->getMateriaux()->getNom();
        }else{
            $this->setPrix($prixTotal);
            return null;
        }
        $prixMetreCarre = null;
        if($type == 'Lambris'){
            if($materiel == 'Bois'){
                $prixMetreCarre = 10;
            }elseif($materiel == 'Pin'){
                $prixMetreCarre = 20;
            }elseif($materiel == 'Sapin'){
                $prixMetreCarre = 20;
            }elseif($materiel == 'Melzene'){
                $prixMetreCarre = 25;
            }elseif($materiel == 'Chatignier'){
                $prixMetreCarre = 30;
            }elseif($materiel == 'Bambou'){
                $prixMetreCarre = 45;
            }else{//On n'a pas encore tous les prix
                $prixMetreCarre = 0;
            }
        }elseif($type == 'Porte'){
            if($materiel == 'Bois'){
                $prixMetreCarre = 200;
            }elseif($materiel == 'PVC'){
                $prixMetreCarre = 120;
            }elseif($materiel == 'Alu'){
                $prixMetreCarre = 200;
            }elseif($materiel == 'Verre'){
                $prixMetreCarre = 250;
            }else{//on n'a pas encore tous les prix
                $prixMetreCarre = 200;
            }
        }else{//on n'a pas encore tous les types
            $prixMetreCarre = 0;
        }

        if($this->getDimension()->getLongueur() and $this->getDimension()->getLargeur() and $this->getQuantite()){
            $prixTotal = ($this->getDimension()->getLargeur() * $this->getDimension()->getLongueur()) * $prixMetreCarre * $this->getQuantite();
            $this->setPrix($prixTotal);
            return $prixTotal;
        }else{
            $this->setPrix($prixTotal);
            return null;
        }
    }

    /**
     * Calculer le prix du second oeuvre
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
