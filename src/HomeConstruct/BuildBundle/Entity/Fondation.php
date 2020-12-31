<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Fondation
 *
 * @ORM\Table(name="home_construct_fondation")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\FondationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Fondation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \TypeFondation
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\TypeFondation", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=false)
     */
    private $prixMainDoeuvre;

    /**
     * @var \Ferraillage
     *
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\Ferraillage", cascade={"persist","remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $ferraillage;

    /**
     * @var float
     *
     * @ORM\Column(type="float", precision=255, scale=0, nullable=true)
     */
    private $prix;

    /**
     * @var \GrosOeuvre
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\GrosOeuvre", inversedBy="fondation"))
     * @ORM\JoinColumn(nullable=false)
     */
    private $grosOeuvre;

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
     * Set prixMainDoeuvre.
     *
     * @param float $prixMainDoeuvre
     *
     * @return Fondation
     */
    public function setPrixMainDoeuvre($prixMainDoeuvre)
    {
        $this->prixMainDoeuvre = $prixMainDoeuvre;

        return $this;
    }

    /**
     * Get prixMainDoeuvre.
     *
     * @return float
     */
    public function getPrixMainDoeuvre()
    {
        return $this->prixMainDoeuvre;
    }

    /**
     * Set ferraillage.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Ferraillage|null $ferraillage
     *
     * @return Fondation
     */
    public function setFerraillage(\HomeConstruct\BuildBundle\Entity\Ferraillage $ferraillage = null)
    {
        $this->ferraillage = $ferraillage;

        return $this;
    }

    /**
     * Get ferraillage.
     *
     * @return \HomeConstruct\BuildBundle\Entity\Ferraillage|null
     */
    public function getFerraillage()
    {
        return $this->ferraillage;
    }

    /**
     * Set type.
     *
     * @param \HomeConstruct\BuildBundle\Entity\TypeFondation $type
     *
     * @return Fondation
     */
    public function setType(\HomeConstruct\BuildBundle\Entity\TypeFondation $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return \HomeConstruct\BuildBundle\Entity\TypeFondation
     */
    public function getType()
    {
        return $this->type;
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

    public function getGrosOeuvre(): ?GrosOeuvre
    {
        return $this->grosOeuvre;
    }

    public function setGrosOeuvre(GrosOeuvre $grosOeuvre): self
    {
        $this->grosOeuvre = $grosOeuvre;

        return $this;
    }

    public function getTarifFerraillage(){
        $tarifFerraillage=null;
        if($this->getGrosOeuvre()->getInformationBase()){
            $surface=$this->getGrosOeuvre()->getInformationBase()->getSurfaceTotale();
            $tarifFerraillage=30*$surface;
        }
        return $tarifFerraillage;
    }

    public function getTarifBeton(){
        $tarifBeton=null;
        if($this->getGrosOeuvre()->getEtudeSol() and $this->getGrosOeuvre()->getInformationBase()){
            $profondeur=$this->getGrosOeuvre()->getEtudeSol()->getProfondeurChoisie();
            $surface=$this->getGrosOeuvre()->getInformationBase()->getSurfaceTotale();
            $tarifBeton=(90*($surface*$profondeur))+300;
        }
        return $tarifBeton;
    }

    public function getTarifSol(){
        $tarifSol=null;
        if($this->getGrosOeuvre()->getEtudeSol()){
            $typeSol=$this->getGrosOeuvre()->getEtudeSol()->getType()->getNom();
            if($this->getGrosOeuvre()->getInformationBase()){
                $surface=$this->getGrosOeuvre()->getInformationBase()->getSurfaceTotale();
                if($typeSol=='Argileux' or $typeSol=='Sableux'){
                    $tarifSol=500*$surface;
                }else{
                    $tarifSol=150*$surface;
                }
                if($this->getGrosOeuvre()->getSoubassement()){
                    $typeSoubassement=$this->getGrosOeuvre()->getSoubassement()->getType()->getNom();
                    if($typeSoubassement=='Vide sanitaire'){
                        $tarifSol+=150*$surface;
                    }
                }
            }
        }
        return $tarifSol;
    }

    public function getPrixTotalMainDoeuvre(){
        $surface=$this->getGrosOeuvre()->getInformationBase()->getSurfaceTotale();
        return $this->getPrixMainDoeuvre()*$surface;
}

    /**
     * Calcule le prix total et le set au prix des fondations.
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function calculPrix()
    {
        $prixTotal=($this->getTarifFerraillage())+($this->getTarifBeton())+($this->getTarifSol())+($this->getPrixTotalMainDoeuvre());
        $this->setPrix($prixTotal);
        return $prixTotal;
    }

    /**
     * Calculer le prix du gros oeuvre
     *
     * @ORM\PostPersist
     * @ORM\PostUpdate
     */
    public function appelCalculPrixGO(){
        $grosOeuvre=$this->getGrosOeuvre();
        $grosOeuvre->calculPrix();
    }

    function getEntityName(){
        return 'Fondation';
    }
}
