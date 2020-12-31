<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Excavation
 *
 * @ORM\Table(name="home_construct_escalier")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\ExcavationRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 */
class Escalier
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
     * @var float
     *
     * @ORM\Column(name="prix", type="float", length=64, nullable=false)
     */
    private $prix;

    /**
     * @var \MateriauxEscalier
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\MateriauxEscalier", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $materiaux;

    /**
     * @var \TypeMenuiserieInterieure
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\TypeEscalier", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @var int|null
     *
     * @ORM\Column(name="quantite", type="integer", length=64, nullable=false)
     */
    private $quantite;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\SecondOeuvre", cascade={"persist"}, inversedBy="escaliers"))
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
     * Calculer le prix de l'escalier
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function calculPrix($em){
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
        if($type == 'Escalier Droit'){
            if($materiel == 'Bois'){
                $prixTotal = 6000;
            }elseif($materiel == 'Béton'){
                $prixTotal = 4000;
            }elseif($materiel == 'Inox'){
                $prixTotal = 12000;
            }elseif($materiel == 'Alu'){
                $prixTotal = 15000;
            }elseif($materiel == 'Acier'){
                $prixTotal = 6000;
            }elseif($materiel == 'Verre'){
                $prixTotal = 5000;
            }elseif($materiel == 'Pierre'){
                $prixTotal = 5000;
            }
        }elseif($type == 'Escalier Tournant'){
            if($materiel == 'Bois'){
                $prixTotal = 7000;
            }elseif($materiel == 'Béton'){
                $prixTotal = 3000;
            }elseif($materiel == 'Inox'){
                $prixTotal = 12000;
            }elseif($materiel == 'Alu'){
                $prixTotal = 15000;
            }elseif($materiel == 'Acier'){
                $prixTotal = 6000;
            }elseif($materiel == 'Verre'){
                $prixTotal = 7500;
            }elseif($materiel == 'Pierre'){
                $prixTotal = 7000;
            }
        }elseif($type == 'Escalier Hélicoïdal'){
            if($materiel == 'Bois'){
                $prixTotal = 8000;
            }elseif($materiel == 'Béton'){
                $prixTotal = 4000;
            }elseif($materiel == 'Inox'){
                $prixTotal = 12000;
            }elseif($materiel == 'Alu'){
                $prixTotal = 15000;
            }elseif($materiel == 'Acier'){
                $prixTotal = 6000;
            }elseif($materiel == 'Verre'){
                $prixTotal = 1200;
            }elseif($materiel == 'Pierre'){
                $prixTotal = 12000;
            }
        }
        $prixTotal = $prixTotal * $this->getQuantite();
        $this->setPrix($prixTotal);
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


    public function getEntityName(){
        return 'Escalier';
    }


    /**
     * Set prix.
     *
     * @param float $prix
     *
     * @return Escalier
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
     * Set materiaux.
     *
     * @param \HomeConstruct\BuildBundle\Entity\MateriauxEscalier $materiaux
     *
     * @return Escalier
     */
    public function setMateriaux(\HomeConstruct\BuildBundle\Entity\MateriauxEscalier $materiaux)
    {
        $this->materiaux = $materiaux;

        return $this;
    }

    /**
     * Get materiaux.
     *
     * @return \HomeConstruct\BuildBundle\Entity\MateriauxEscalier
     */
    public function getMateriaux()
    {
        return $this->materiaux;
    }

    /**
     * Set type.
     *
     * @param \HomeConstruct\BuildBundle\Entity\TypeEscalier $type
     *
     * @return Escalier
     */
    public function setType(\HomeConstruct\BuildBundle\Entity\TypeEscalier $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return \HomeConstruct\BuildBundle\Entity\TypeEscalier
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
     * @return Escalier
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
     * @return Escalier
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
}
