<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManagerInterface;

/**
 * MenuiserieExterieure
 *
 * @ORM\Table(name="home_construct_menuiserie_exterieure")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\MenuiserieExterieureRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class MenuiserieExterieure
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
     * @var \DimensionMenuiserieExterieure
     *
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\DimensionMenuiserieExterieure", cascade={"persist","remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $dimension;

    /**
     * @var \TypeMenuiserieExterieure
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\TypeMenuiserieExterieure", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @var \MateriauxMenuiserieExterieure
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\MateriauxMenuiserieExterieure", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $materiaux;

    /**
     * @var \GrosOeuvre
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\GrosOeuvre", cascade={"persist"}, inversedBy="menuiseriesExterieures"))
     * @ORM\JoinColumn(nullable=false)
     */
    private $grosOeuvre;

    /**
     * @var int
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
     * Set dimension.
     *
     * @param \HomeConstruct\BuildBundle\Entity\DimensionMenuiserieExterieure $dimension
     *
     * @return MenuiserieExterieure
     */
    public function setDimension(\HomeConstruct\BuildBundle\Entity\DimensionMenuiserieExterieure $dimension)
    {
        $this->dimension = $dimension;

        return $this;
    }

    /**
     * Get dimension.
     *
     * @return \HomeConstruct\BuildBundle\Entity\DimensionMenuiserieExterieure
     */
    public function getDimension()
    {
        return $this->dimension;
    }


    /**
     * Set grosOeuvre.
     *
     * @param \HomeConstruct\BuildBundle\Entity\GrosOeuvre $grosOeuvre
     *
     * @return MenuiserieExterieure
     */
    public function setGrosOeuvre(\HomeConstruct\BuildBundle\Entity\GrosOeuvre $grosOeuvre)
    {
        $this->grosOeuvre = $grosOeuvre;

        return $this;
    }

    /**
     * Get grosOeuvre.
     *
     * @return \HomeConstruct\BuildBundle\Entity\GrosOeuvre
     */
    public function getGrosOeuvre()
    {
        return $this->grosOeuvre;
    }

    /**
     * Get types.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTypes()
    {
        return $this->types;
    }

    public function __toString() {
        return 'Menuiserie exterieure numéro '.$this->getId();
    }

    /**
     * Set quantite.
     *
     * @param int $quantite
     *
     * @return MenuiserieExterieure
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
     * @return MenuiserieExterieure
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
     * Set type.
     *
     * @param \HomeConstruct\BuildBundle\Entity\TypeMenuiserieExterieure $type
     *
     * @return MenuiserieExterieure
     */
    public function setType(\HomeConstruct\BuildBundle\Entity\TypeMenuiserieExterieure $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return \HomeConstruct\BuildBundle\Entity\TypeMenuiserieExterieure
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set materiaux.
     *
     * @param \HomeConstruct\BuildBundle\Entity\MateriauxMenuiserieExterieure $materiaux
     *
     * @return MenuiserieExterieure
     */
    public function setMateriaux(\HomeConstruct\BuildBundle\Entity\MateriauxMenuiserieExterieure $materiaux)
    {
        $this->materiaux = $materiaux;

        return $this;
    }

    /**
     * Get materiaux.
     *
     * @return \HomeConstruct\BuildBundle\Entity\MateriauxMenuiserieExterieure
     */
    public function getMateriaux()
    {
        return $this->materiaux;
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
        if($type == 'Fenêtre'){
            if($materiel == 'PVC'){
                $prixMetreCarre = 170;
            }elseif($materiel == 'Alu'){
                $prixMetreCarre = 400;
            }elseif($materiel == 'Bois'){
                $prixMetreCarre = 250;
            }else{//On n'a pas encore tous les prix
                $prixMetreCarre = 200;
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
        }elseif($type == 'Véranda'){
            if($materiel == 'Bois' ){
                $prixMetreCarre = 1700;
            }elseif($materiel == 'Alu') {
                $prixMetreCarre = 1200;
            }else{//on n'a pas encore tous les prix
                $prixMetreCarre = 1000;
            }
        }else{//on n'a pas encore tous les types
            $prixMetreCarre = 200;
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
     * Calculer le prix du gros oeuvre
     *
     * @ORM\PostPersist
     * @ORM\PostUpdate
     */
    public function appelCalculPrixGO(){
        $em=$this->em;
        $grosOeuvre=$this->getGrosOeuvre();
        $grosOeuvre->calculPrix();
        $em->persist($grosOeuvre);
        $em->flush();
        $projet=$grosOeuvre->getProjet();
        $em->persist($projet);
        $em->flush();
    }

    public function getEntityName(){
        return 'Menuiserie Exterieure';
    }
}
