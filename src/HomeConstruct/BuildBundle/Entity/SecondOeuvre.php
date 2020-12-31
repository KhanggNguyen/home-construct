<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * SecondOeuvre
 *
 * @ORM\Table(name="home_construct_second_oeuvre")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\SecondOeuvreRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class SecondOeuvre
{
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
     * @ORM\OneToMany(targetEntity="HomeConstruct\BuildBundle\Entity\Domotique", cascade={"persist","remove"}, mappedBy="secondOeuvre")
     * @ORM\JoinColumn(nullable=false, onDelete="SET NULL")
     */
    private $domotiques;

    /**
     * @var \Isolation
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\Isolation", cascade={"persist","remove"},mappedBy="secondOeuvre")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $isolation;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="HomeConstruct\BuildBundle\Entity\Climatisation", cascade={"persist","remove"}, mappedBy="secondOeuvre")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $climatisation;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="HomeConstruct\BuildBundle\Entity\Chauffage", cascade={"persist","remove"}, mappedBy="secondOeuvre")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $chauffage;

    /**
     * @var \EnduitExterieur
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\EnduitExterieur", cascade={"persist","remove"},mappedBy="secondOeuvre")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $enduitExterieur;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="HomeConstruct\BuildBundle\Entity\Plomberie", cascade={"persist","remove"},mappedBy="secondOeuvre")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $plomberies;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="HomeConstruct\BuildBundle\Entity\MenuiserieInterieure", cascade={"persist","remove"}, mappedBy="secondOeuvre")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $menuiseriesInterieures;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="HomeConstruct\BuildBundle\Entity\Escalier", cascade={"persist","remove"}, mappedBy="secondOeuvre")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $escaliers;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="HomeConstruct\BuildBundle\Entity\RevetementSol", cascade={"persist","remove"}, mappedBy="secondOeuvre")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $revetementSol;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="HomeConstruct\BuildBundle\Entity\Evacuation", cascade={"persist","remove"}, mappedBy="secondOeuvre")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $evacuations;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\OneToMany(targetEntity="HomeConstruct\BuildBundle\Entity\Ventilation", cascade={"persist","remove"}, mappedBy="secondOeuvre")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $ventilations;

    /**
     * @var \Projet
     *
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\Projet", cascade={"persist"}, mappedBy="secondOeuvre")
     * @ORM\JoinColumn(nullable=false)
     */
    private $projet;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float", precision=255, scale=0, nullable=true)
     */
    private $prix;


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
     * Constructor
     */
    public function __construct()
    {
        $this->domotiques = new \Doctrine\Common\Collections\ArrayCollection();
        $this->menuiseriesInterieures = new \Doctrine\Common\Collections\ArrayCollection();
        $this->escaliers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->climatisation = new \Doctrine\Common\Collections\ArrayCollection();
        $this->chauffage = new \Doctrine\Common\Collections\ArrayCollection();
        $this->revetementSol = new \Doctrine\Common\Collections\ArrayCollection();
        $this->evacuations = new ArrayCollection();
        $this->ventilations = new ArrayCollection();
        $this->plomberies = new ArrayCollection();
    }


    /**
     * Set isolation.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Isolation $isolation
     *
     * @return SecondOeuvre
     */
    public function setIsolation(\HomeConstruct\BuildBundle\Entity\Isolation $isolation)
    {
        $this->isolation = $isolation;

        return $this;
    }

    /**
     * Get isolation.
     *
     * @return \HomeConstruct\BuildBundle\Entity\Isolation
     */
    public function getIsolation()
    {
        return $this->isolation;
    }

    /**
     * Set enduitExterieur.
     *
     * @param \HomeConstruct\BuildBundle\Entity\EnduitExterieur $enduitExterieur
     *
     * @return SecondOeuvre
     */
    public function setEnduitExterieur(\HomeConstruct\BuildBundle\Entity\EnduitExterieur $enduitExterieur)
    {
        $this->enduitExterieur = $enduitExterieur;

        return $this;
    }

    /**
     * Set revetementSol.
     *
     * @param \HomeConstruct\BuildBundle\Entity\RevetementSol $revetementSol
     *
     * @return SecondOeuvre
     */
    public function setRevetementSol(\HomeConstruct\BuildBundle\Entity\RevetementSol $revetementSol)
    {
        $this->revetementSol = $revetementSol;

        return $this;
    }

    /**
     * Get enduitExterieur.
     *
     * @return \HomeConstruct\BuildBundle\Entity\EnduitExterieur
     */
    public function getEnduitExterieur()
    {
        return $this->enduitExterieur;
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
     * Calcule le prix total et le set au prix du second oeuvre.
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function calculPrix()
    {
        if($this->getDomotiques()!=null){
            $prixDomotiques=$this->calculPrixDomotiques();
        }
        if($this->getIsolation()!=null){
            if($this->getIsolation()->getId()){
                $prixIsolation=$this->getIsolation()->getPrixTotal();
            }else{
                $prixIsolation=null;
            }
        }else{
            $prixIsolation=null;
        }
        if($this->getEnduitExterieur()!=null){
            if($this->getEnduitExterieur()->getId()) {
                $prixEnduitExterieur = $this->getEnduitExterieur()->getPrixTotal();
            }else{
                $prixEnduitExterieur = null;
            }
        }else{
            $prixEnduitExterieur = null;
        }
        if($this->getMenuiseriesInterieures()!=null){
            $prixMenuiseriesInterieures=$this->calculPrixMenuiseriesInterieures();
        }else{
            $prixMenuiseriesInterieures=null;
        }
        if($this->getEscaliers()!=null){
            $prixEscaliers=$this->calculPrixEscaliers();
        }else{
            $prixEscaliers=null;
        }
        if($this->getClimatisation()!=null){
            $prixClimatisation=$this->calculPrixClimatisations();
        }else{
            $prixClimatisation=null;
        }
        if($this->getChauffage()!=null){
            $prixChauffage=$this->calculPrixChauffages();
        }else{
            $prixChauffage=null;
        }
        if($this->getRevetementSol()!=null){
            $prixRevetementSol=$this->calculPrixRevetementSol();
        }else{
            $prixRevetementSol=null;
        }
        if($this->getVentilations()!=null){
            $prixVentilation=$this->calculPrixVentilations();
        }else{
            $prixVentilation=null;
        }
        if($this->getEvacuations()!=null){
            $prixEvacuation=$this->calculPrixEvacuations();
        }else{
            $prixEvacuation=null;
        }
        if($this->getPlomberies()!=null){
            $prixPlomberie = $this->calculPrixPlomberies();
        }else{
            $prixPlomberie=null;
        }
        $this->setPrix($prixDomotiques+$prixIsolation+$prixEnduitExterieur+$prixMenuiseriesInterieures+$prixEscaliers+$prixChauffage+$prixClimatisation+$prixRevetementSol+$prixVentilation+$prixEvacuation+$prixPlomberie);

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

    public function getEntityName(){
        return 'Second Oeuvre';
    }

    /**
     * Set projet.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Projet $projet
     *
     * @return SecondOeuvre
     */
    public function setProjet(\HomeConstruct\BuildBundle\Entity\Projet $projet)
    {
        $this->projet = $projet;

        return $this;
    }

    /**
     * Get projet.
     *
     * @return \HomeConstruct\BuildBundle\Entity\Projet
     */
    public function getProjet()
    {
        return $this->projet;
    }

    /**
     * Add escalier.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Escalier $escalier
     *
     * @return SecondOeuvre
     */
    public function addEscalier(\HomeConstruct\BuildBundle\Entity\Escalier $escalier)
    {
        $this->escaliers[] = $escalier;

        return $this;
    }

    /**
     * Remove escalier.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Escalier $escalier
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeEscalier(\HomeConstruct\BuildBundle\Entity\Escalier $escalier)
    {
        return $this->escaliers->removeElement($escalier);
    }

    /**
     * Get escaliers.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEscaliers()
    {
        return $this->escaliers;
    }

    /**
     * Add revetementSol.
     *
     * @param \HomeConstruct\BuildBundle\Entity\RevetementSol $revetementSol
     *
     * @return SecondOeuvre
     */
    public function addRevetementSol(\HomeConstruct\BuildBundle\Entity\RevetementSol $revetementSol)
    {
        $this->revetementSol[] = $revetementSol;

        return $this;
    }

    /**
     * Remove revetementSol.
     *
     * @param \HomeConstruct\BuildBundle\Entity\RevetementSol $revetementSol
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeRevetementSol(\HomeConstruct\BuildBundle\Entity\RevetementSol $revetementSol)
    {
        return $this->revetementSol->removeElement($revetementSol);
    }

    /**
     * Get revetementSol.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRevetementSol()
    {
        return $this->revetementSol;
    }

    /**
     * Calculer le prix total du revêtement des sols
     *
     */
    public function calculPrixRevetementSol(){
        $prixTotal = null;
        foreach($this->getRevetementSol() as $revetementSol){
            if($revetementSol->getPrix()){
                $prixTotal += $revetementSol->getPrix();
            }
        }
        return $prixTotal;
    }

    /**
     * Calculer le prix total des evacuations
     *
     */
    public function calculPrixEvacuations(){
        $prixTotal = null;
        foreach($this->getEvacuations() as $evacuation){
            if($evacuation->getPrix()){
                $prixTotal += $evacuation->getPrix();
            }
        }
        return $prixTotal;
    }

    /**
     * Calculer le prix total des ventilations
     *
     */
    public function calculPrixVentilations(){
        $prixTotal = null;
        foreach($this->getVentilations() as $ventilation){
            if($ventilation->getPrix()){
                $prixTotal += $ventilation->getPrix();
            }
        }
        return $prixTotal;
    }

    /**
     * Calculer le prix total des domotiques
     *
     */
    public function calculPrixDomotiques(){
        $prixTotal = null;
        foreach($this->getDomotiques() as $domotique){
            if($domotique->getPrix()){
                $prixTotal += $domotique->getPrix();
            }
        }
        return $prixTotal;
    }

    /**
     * Calculer le prix total des menuiseries interieures
     *
     */
    public function calculPrixMenuiseriesInterieures(){
        $prixTotal = null;
        foreach($this->getMenuiseriesInterieures() as $menuiserie){
            if($menuiserie->getPrix()){
                $prixTotal += $menuiserie->getPrix();
            }
        }
        return $prixTotal;
    }

    public function calculPrixClimatisations(){
        $prixTotal = null;
        foreach($this->getClimatisation() as $climatisation){
            if($climatisation->getPrix()){
                $prixTotal += $climatisation->getPrix();
            }
        }
        return $prixTotal;
    }
    public function calculPrixChauffages(){
        $prixTotal = null;
        foreach($this->getChauffage() as $chauffage){
            if($chauffage->getPrix()){
                $prixTotal += $chauffage->getPrix();
            }
        }
        return $prixTotal;
    }

    /**
     * Add climatisation.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Climatisation $climatisation
     *
     * @return SecondOeuvre
     */
    public function addClimatisation(\HomeConstruct\BuildBundle\Entity\Climatisation $climatisation)
    {
        $this->climatisation[] = $climatisation;

        return $this;
    }

    /**
     * Remove climatisation.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Climatisation $climatisation
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeClimatisation(\HomeConstruct\BuildBundle\Entity\Climatisation $climatisation)
    {
        return $this->climatisation->removeElement($climatisation);
    }

    /**
     * Get climatisation.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getClimatisation()
    {
        return $this->climatisation;
    }

    /**
     * Add chauffage.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Chauffage $chauffage
     *
     * @return SecondOeuvre
     */
    public function addChauffage(\HomeConstruct\BuildBundle\Entity\Chauffage $chauffage)
    {
        $this->chauffage[] = $chauffage;

        return $this;
    }

    /**
     * Remove chauffage.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Chauffage $chauffage
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeChauffage(\HomeConstruct\BuildBundle\Entity\Chauffage $chauffage)
    {
        return $this->chauffage->removeElement($chauffage);
    }

    /**
     * Get chauffage.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChauffage()
    {
        return $this->chauffage;
    }

    /**
     * Calculer le prix total des escaliers
     *
     */
    public function calculPrixEscaliers(){
        $prixTotal = null;
        foreach($this->getEscaliers() as $escalier){
            if($escalier->getPrix()){
                $prixTotal += $escalier->getPrix();
            }
        }
        return $prixTotal;
    }

    /**
     * @return Collection|Domotique[]
     */
    public function getDomotiques(): Collection
    {
        return $this->domotiques;
    }

    public function addDomotique(Domotique $domotique): self
    {
        if (!$this->domotiques->contains($domotique)) {
            $this->domotiques[] = $domotique;
            $domotique->setSecondOeuvre($this);
        }

        return $this;
    }

    public function removeDomotique(Domotique $domotique): self
    {
        if ($this->domotiques->contains($domotique)) {
            $this->domotiques->removeElement($domotique);
            // set the owning side to null (unless already changed)
            if ($domotique->getSecondOeuvre() === $this) {
                $domotique->setSecondOeuvre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Evacuation[]
     */
    public function getEvacuations(): Collection
    {
        return $this->evacuations;
    }

    public function addEvacuation(Evacuation $evacuation): self
    {
        if (!$this->evacuations->contains($evacuation)) {
            $this->evacuations[] = $evacuation;
            $evacuation->setSecondOeuvre($this);
        }

        return $this;
    }

    public function removeEvacuation(Evacuation $evacuation): self
    {
        if ($this->evacuations->contains($evacuation)) {
            $this->evacuations->removeElement($evacuation);
            // set the owning side to null (unless already changed)
            if ($evacuation->getSecondOeuvre() === $this) {
                $evacuation->setSecondOeuvre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Ventilation[]
     */
    public function getVentilations(): Collection
    {
        return $this->ventilations;
    }

    public function addVentilation(Ventilation $ventilation): self
    {
        if (!$this->ventilations->contains($ventilation)) {
            $this->ventilations[] = $ventilation;
            $ventilation->setSecondOeuvre($this);
        }

        return $this;
    }

    public function removeVentilation(Ventilation $ventilation): self
    {
        if ($this->ventilations->contains($ventilation)) {
            $this->ventilations->removeElement($ventilation);
            // set the owning side to null (unless already changed)
            if ($ventilation->getSecondOeuvre() === $this) {
                $ventilation->setSecondOeuvre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MenuiserieInterieure[]
     */
    public function getMenuiseriesInterieures(): Collection
    {
        return $this->menuiseriesInterieures;
    }

    public function addMenuiseriesInterieure(MenuiserieInterieure $menuiseriesInterieure): self
    {
        if (!$this->menuiseriesInterieures->contains($menuiseriesInterieure)) {
            $this->menuiseriesInterieures[] = $menuiseriesInterieure;
            $menuiseriesInterieure->setSecondOeuvre($this);
        }

        return $this;
    }

    public function removeMenuiseriesInterieure(MenuiserieInterieure $menuiseriesInterieure): self
    {
        if ($this->menuiseriesInterieures->contains($menuiseriesInterieure)) {
            $this->menuiseriesInterieures->removeElement($menuiseriesInterieure);
            // set the owning side to null (unless already changed)
            if ($menuiseriesInterieure->getSecondOeuvre() === $this) {
                $menuiseriesInterieure->setSecondOeuvre(null);
            }
        }

        return $this;
    }



    /**
     * Add plomberie.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Plomberie $plomberie
     *
     * @return SecondOeuvre
     */
    public function addPlomberie(\HomeConstruct\BuildBundle\Entity\Plomberie $plomberie)
    {
        $this->plomberies[] = $plomberie;

        return $this;
    }

    /**
     * Remove plomberie.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Plomberie $plomberie
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePlomberie(\HomeConstruct\BuildBundle\Entity\Plomberie $plomberie)
    {
        return $this->plomberies->removeElement($plomberie);
    }

    /**
     * Get plomberies.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlomberies()
    {
        return $this->plomberies;
    }

    /**
     * Calculer le prix total des plomberies
     *
     */
    public function calculPrixPlomberies(){
        $prixTotal = null;
        foreach($this->getPlomberies() as $plomberie){
            if($plomberie->getPrixTotal()){
                $prixTotal += $plomberie->getPrixTotal();
            }
        }
        return $prixTotal;
    }
}
