<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManagerInterface;

/**
 * GrosOeuvre
 *
 * @ORM\Table(name="home_construct_gros_oeuvre")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\GrosOeuvreRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class GrosOeuvre
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
     * @var \InformationBase
     *
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\InformationBase", cascade={"persist","remove"},mappedBy="grosOeuvre")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $informationBase;

    /**
     * @var \Charpente
     *
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\Charpente", cascade={"persist","remove"},mappedBy="grosOeuvre")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $charpente;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="HomeConstruct\BuildBundle\Entity\Piece", mappedBy="grosOeuvre",cascade={"remove"})
     * @ORM\JoinColumn(nullable=false, onDelete="SET NULL")
     */
    private $pieces;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="HomeConstruct\BuildBundle\Entity\MenuiserieExterieure", cascade={"persist","remove"}, mappedBy="grosOeuvre")
     * @ORM\JoinColumn(nullable=false, onDelete="SET NULL")
     */
    private $menuiseriesExterieures;

    /**
     * @var \EtudeSol
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\EtudeSol", cascade={"persist","remove"},mappedBy="grosOeuvre")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $etudeSol;

    /**
     * @var \Elevation
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\Elevation", cascade={"persist","remove"},mappedBy="grosOeuvre")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $elevation;

    /**
     * @var \Plancher
     * @ORM\OneToMany(targetEntity="HomeConstruct\BuildBundle\Entity\Plancher", cascade={"persist","remove"}, mappedBy="grosOeuvre"))
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $plancher;

    /**
     * @var \PrepaAccesTerrain
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\PrepaAccesTerrain", cascade={"persist","remove"},mappedBy="grosOeuvre")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $prepaAccesTerrain;

    /**
     * @var \Excavation
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\Excavation", cascade={"persist","remove"},mappedBy="grosOeuvre")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $excavation;

    /**
     * @var \Fondation
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\Fondation", cascade={"persist","remove"},mappedBy="grosOeuvre")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $fondation;

    /**
     * @var \Soubassement
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\Soubassement", cascade={"persist","remove"}, mappedBy="grosOeuvre")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $soubassement;

    /**
     * @var \Vrd
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\Vrd", cascade={"persist","remove"}, mappedBy="grosOeuvre")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $vrd;

    /**
     * @var \Toiture
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\Toiture", cascade={"persist","remove"},mappedBy="grosOeuvre")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $toiture;

    /**
     * @var float
     *
     * @ORM\Column(type="float", precision=255, scale=0, nullable=true)
     */
    private $prix;

    /**
     * @var \Projet
     *
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\Projet", cascade={"persist"},mappedBy="grosOeuvre")
     * @ORM\JoinColumn(nullable=false)
     */
    private $projet;

    /**
     * Constructor
     */
    public function __construct(EntityManagerInterface $entityManager=null)
    {
        $this->pieces = new ArrayCollection();
        $this->menuiseriesExterieures = new ArrayCollection();
        $this->plancher = new ArrayCollection();
        if($entityManager){
            $this->em = $entityManager;
        }
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
     * Set informationBase.
     *
     * @param \HomeConstruct\BuildBundle\Entity\InformationBase $informationBase
     *
     * @return GrosOeuvre
     */
    public function setInformationBase(InformationBase $informationBase)
    {
        $this->informationBase = $informationBase;

        return $this;
    }

    /**
     * Get informationBase.
     *
     * @return \HomeConstruct\BuildBundle\Entity\InformationBase
     */
    public function getInformationBase()
    {
        return $this->informationBase;
    }

    /**
     * Set charpente.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Charpente|null $charpente
     *
     * @return GrosOeuvre
     */
    public function setCharpente(Charpente $charpente = null)
    {
        $this->charpente = $charpente;

        return $this;
    }

    /**
     * Get charpente.
     *
     * @return \HomeConstruct\BuildBundle\Entity\Charpente|null
     */
    public function getCharpente()
    {
        return $this->charpente;
    }

    /**
     * Add piece.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Piece $piece
     *
     * @return GrosOeuvre
     */
    public function addPiece(Piece $piece)
    {
        $this->pieces[] = $piece;

        return $this;
    }

    /**
     * Remove piece.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Piece $piece
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePiece(Piece $piece)
    {
        return $this->pieces->removeElement($piece);
    }

    /**
     * Get pieces.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPieces()
    {
        return $this->pieces;
    }

    /**
     * Add menuiseriesExterieure.
     *
     * @param \HomeConstruct\BuildBundle\Entity\MenuiserieExterieure $menuiseriesExterieure
     *
     * @return GrosOeuvre
     */
    public function addMenuiseriesExterieure(MenuiserieExterieure $menuiseriesExterieure)
    {
        $this->menuiseriesExterieures[] = $menuiseriesExterieure;

        return $this;
    }

    /**
     * Remove menuiseriesExterieure.
     *
     * @param \HomeConstruct\BuildBundle\Entity\MenuiserieExterieure $menuiseriesExterieure
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeMenuiseriesExterieure(MenuiserieExterieure $menuiseriesExterieure)
    {
        return $this->menuiseriesExterieures->removeElement($menuiseriesExterieure);
    }

    /**
     * Get menuiseriesExterieures.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMenuiseriesExterieures()
    {
        return $this->menuiseriesExterieures;
    }

    /**
     * Set etudeSol.
     *
     * @param \HomeConstruct\BuildBundle\Entity\EtudeSol|null $etudeSol
     *
     * @return GrosOeuvre
     */
    public function setEtudeSol(EtudeSol $etudeSol = null)
    {
        $this->etudeSol = $etudeSol;

        return $this;
    }

    /**
     * Get etudeSol.
     *
     * @return \HomeConstruct\BuildBundle\Entity\EtudeSol|null
     */
    public function getEtudeSol()
    {
        return $this->etudeSol;
    }

    /**
     * Set prepaAccesTerrain.
     *
     * @param \HomeConstruct\BuildBundle\Entity\PrepaAccesTerrain|null $prepaAccesTerrain
     *
     * @return GrosOeuvre
     */
    public function setPrepaAccesTerrain(PrepaAccesTerrain $prepaAccesTerrain = null)
    {
        $this->prepaAccesTerrain = $prepaAccesTerrain;

        return $this;
    }

    /**
     * Get prepaAccesTerrain.
     *
     * @return \HomeConstruct\BuildBundle\Entity\PrepaAccesTerrain|null
     */
    public function getPrepaAccesTerrain()
    {
        return $this->prepaAccesTerrain;
    }

    /**
     * Set excavation.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Excavation|null $excavation
     *
     * @return GrosOeuvre
     */
    public function setExcavation(Excavation $excavation = null)
    {
        $this->excavation = $excavation;

        return $this;
    }

    /**
     * Get excavation.
     *
     * @return \HomeConstruct\BuildBundle\Entity\Excavation|null
     */
    public function getExcavation()
    {
        return $this->excavation;
    }

    /**
     * Set fondation.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Fondation|null $fondation
     *
     * @return GrosOeuvre
     */
    public function setFondation(Fondation $fondation = null)
    {
        $this->fondation = $fondation;

        return $this;
    }

    /**
     * Get fondation.
     *
     * @return \HomeConstruct\BuildBundle\Entity\Fondation|null
     */
    public function getFondation()
    {
        return $this->fondation;
    }

    /**
     * Set soubassement.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Soubassement|null $soubassement
     *
     * @return GrosOeuvre
     */
    public function setSoubassement(Soubassement $soubassement = null)
    {
        $this->soubassement = $soubassement;

        return $this;
    }

    /**
     * Get soubassement.
     *
     * @return \HomeConstruct\BuildBundle\Entity\Soubassement|null
     */
    public function getSoubassement()
    {
        return $this->soubassement;
    }

    /**
     * Set vrd.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Vrd|null $vrd
     *
     * @return GrosOeuvre
     */
    public function setVrd(Vrd $vrd = null)
    {
        $this->vrd = $vrd;

        return $this;
    }

    /**
     * Get vrd.
     *
     * @return \HomeConstruct\BuildBundle\Entity\Vrd|null
     */
    public function getVrd()
    {
        return $this->vrd;
    }

    /**
     * Set toiture.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Toiture|null $toiture
     *
     * @return GrosOeuvre
     */
    public function setToiture(Toiture $toiture = null)
    {
        $this->toiture = $toiture;

        return $this;
    }

    /**
     * Get toiture.
     *
     * @return \HomeConstruct\BuildBundle\Entity\Toiture|null
     */
    public function getToiture()
    {
        return $this->toiture;
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
     * Calculer Prix des Plancher
     *
     */
    public function calculPrixPlancher(){
        $prixPlancher=0;
        foreach($this->getPlancher() as $a){
            $prixPlancher += $a->getPrixTotal();
        }
        return $prixPlancher;

    }

    /**
     * Calcule le prix total et le set au prix du gros oeuvre.
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function calculPrix()
    {
        if($this->getToiture()!=null){
            if($this->getToiture()->getId()){
                $prixToiture = ($this->getToiture()->getPrix());
            }else{
                $prixToiture = null;
            }
        }else{
            $prixToiture = null;
        }
        if($this->getCharpente()!=null){
            if($this->getCharpente()->getId()){
                $prixCharpente = ($this->getCharpente()->getPrix());
            }else{
                $prixCharpente = null;
            }
        }else{
            $prixCharpente = null;
        }
        if($this->getMenuiseriesExterieures()!=null){
            /*$menuiseriesExterieures = $this->getMenuiseriesExterieures();
            $prixMenuiseriesExterieures = null;
            foreach ($menuiseriesExterieures as $menuiserieExterieure){
                if($menuiserieExterieure->getId()){
                    $prixMenuiseriesExterieures+=$menuiserieExterieure->getPrix()*$menuiserieExterieure->getQuantite();
                }
            }*/
            $prixMenuiseriesExterieures = $this->calculPrixMenuiseries();
        }else{
            $prixMenuiseriesExterieures = null;
        }
        if($this->getEtudeSol()!=null){
            if($this->getEtudeSol()->getId()){
                $prixEtudeSol=$this->getEtudeSol()->getPrixForfait();
            }else{
                $prixEtudeSol= null;
            }
        }else{
            $prixEtudeSol= null;
        }
        if($this->getExcavation()!=null){
            if($this->getExcavation()->getId()){
                $prixExcavation=$this->getExcavation()->getPrixTotal();
            }else{
                $prixExcavation = null;
            }
        }else{
            $prixExcavation = null;
        }
        if($this->getPrepaAccesTerrain()!=null){
            if($this->getPrepaAccesTerrain()->getId()){
                $prixPrepaAccesTerrain=$this->getPrepaAccesTerrain()->getPrixTotal();
            }else{
                $prixPrepaAccesTerrain = null;
            }
        }else{
            $prixPrepaAccesTerrain = null;
        }
        if($this->getFondation()!=null){
            if($this->getFondation()->getId()){
                $prixFondations=$this->getFondation()->getPrix();
            }else{
                $prixFondations = null;
            }
        }else{
            $prixFondations = null;
        }
        if($this->getSoubassement()!=null){
            if($this->getSoubassement()->getId()){
                $prixSoubassement=$this->getSoubassement()->getPrixTotal();
            }else{
                $prixSoubassement = null;
            }
        }else{
            $prixSoubassement = null;
        }
        if($this->getVrd()!=null){
            if($this->getVrd()->getId()){
                $prixVrd=$this->getVrd()->getPrixTotal();
            }else{
                $prixVrd = null;
            }
        }else{
            $prixVrd = null;
        }
        if($this->getElevation()!=null){
            if($this->getElevation()->getId()){
                $prixElevation=$this->getElevation()->getPrixTotal();
            }else{
                $prixElevation = null;
            }
        }else{
            $prixElevation = null;
        }
        if($this->getPlancher()!=null){
            $prixPlancher=$this->calculPrixPlancher();
        }else{
            $prixPlancher = null;
        }
        $prixTotal=$prixToiture+$prixCharpente+$prixMenuiseriesExterieures+$prixPlancher+$prixElevation+$prixEtudeSol+$prixExcavation+$prixPrepaAccesTerrain+$prixFondations+$prixSoubassement+$prixVrd;
        $this->setPrix($prixTotal);
        return $prixTotal;

    }

    /**
     * Calculer le prix du projet
     *
     * @ORM\PostPersist
     * @ORM\PostUpdate
     */
    public function appelCalculPrixProjet(){
        $projet=$this->getProjet();
        $projet->calculPrix();
    }

    public function getProjet(): ?Projet
    {
        return $this->projet;
    }

    public function setProjet(Projet $projet): self
    {
        $this->projet = $projet;

        return $this;
    }

    /**
     * Set elevation.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Elevation|null $elevation
     *
     * @return GrosOeuvre
     */
    public function setElevation(\HomeConstruct\BuildBundle\Entity\Elevation $elevation = null)
    {
        $this->elevation = $elevation;

        return $this;
    }

    /**
     * Get elevation.
     *
     * @return \HomeConstruct\BuildBundle\Entity\Elevation|null
     */
    public function getElevation()
    {
        return $this->elevation;
    }

    /**
     * Add plancher.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Plancher $plancher
     *
     * @return GrosOeuvre
     */
    public function addPlancher(\HomeConstruct\BuildBundle\Entity\Plancher $plancher)
    {
        $this->plancher[] = $plancher;

        return $this;
    }

    /**
     * Remove plancher.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Plancher $plancher
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePlancher(\HomeConstruct\BuildBundle\Entity\Plancher $plancher)
    {
        return $this->plancher->removeElement($plancher);
    }

    /**
     * Get plancher.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlancher()
    {
        return $this->plancher;
    }

    /**
     * Calculer le prix total des menuiseries Exterieures
     *
     */
    public function calculPrixMenuiseries(){
        $prixTotal = null;
        foreach($this->getMenuiseriesExterieures() as $menuiserie){
            if($menuiserie->getPrix()){
                $prixTotal += $menuiserie->getPrix();
            }
        }
        return $prixTotal;
    }

    public function getEntityName(){
        return 'Gros Oeuvre';
    }

    public function calculSurfacePlancher(){
        $totalSurface = null;
        foreach($this->getPlancher() as $p){
            $totalSurface += $p->getNbM2();
        }
        return $totalSurface;
    }

}
