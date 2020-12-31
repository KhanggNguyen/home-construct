<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Elevation
 *
 * @ORM\Table(name="home_construct_elevation")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\ElevationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Elevation
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
     * @var int
     *
     * @ORM\Column(name="qtePoutre", type="integer", nullable=true)
     */
    private $qtePoutre;

    /**
     * @ORM\OneToMany(targetEntity="HomeConstruct\BuildBundle\Entity\Poutre", cascade={"persist","remove"},mappedBy="elevation")
     * @ORM\JoinColumn(nullable=false)
     */
    private $poutre;

    /**
     * @ORM\OneToMany(targetEntity="HomeConstruct\BuildBundle\Entity\Mur", cascade={"persist","remove"}, mappedBy="elevation")
     * @ORM\JoinColumn(nullable=false)
     */
    private $mur;

    /**
     * @ORM\OneToMany(targetEntity="HomeConstruct\BuildBundle\Entity\AppuisFenetre", cascade={"persist","remove"}, mappedBy="elevation")
     * @ORM\JoinColumn(nullable=false)
     */
    private $appuisFenetre;

    /**
     * @var int
     *
     * @ORM\Column(name="qteMur", type="integer", nullable=true)
     */
    private $qteMur;

    /**
     * @var int
     *
     * @ORM\Column(name="qteAppuisFenetre", type="integer", nullable=true)
     */
    private $qteAppuisFenetre;

    /**
     * @var int
     *
     * @ORM\Column(name="qteLintaux", type="integer", nullable=true)
     */
    private $qteLintaux;

    /**
     * @var float|null
     *
     * @ORM\Column(name="prixLintaux", type="float", precision=255, scale=0, nullable=true)
     */
    private $prixLintaux;

    /**
     * @var float|null
     *
     * @ORM\Column(name="prixTotal", type="float", precision=255, scale=0, nullable=true)
     */
    private $prixTotal;

    /**
     * @var \GrosOeuvre
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\GrosOeuvre", inversedBy="elevation"))
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
     * Get qteAppuis.
     *
     * @return int
     */
    public function getQteAppuisFenetre()
    {
        return $this->qteAppuisFenetre;
    }

    /**
     * Get qteMur.
     *
     * @return int
     */
    public function getQteMur()
    {
        return $this->qteMur;
    }

    /**
     * Get qtePoutre.
     *
     * @return int
     */
    public function getQtePoutre()
    {
        return $this->qtePoutre;
    }

    /**
     * Get qteLintaux.
     *
     * @return int
     */
    public function getQteLintaux()
    {
        return $this->qteLintaux;
    }

    /**
     * Get poutre.
     *
     * @return int
     */
    public function getPoutre()
    {
        return $this->poutre;
    }

    /**
     * Get mur.
     *
     * @return int
     */
    public function getMur()
    {
        return $this->mur;
    }

    /**
     * Get appuis.
     *
     * @return int
     */
    public function getAppuisFenetre()
    {
        return $this->appuisFenetre;
    }

    /**
     * Get prixLintaux.
     *
     * @return float
     */
    public function getPrixLintaux()
    {
        return $this->prixLintaux;
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
     * Set qteAppuis.
     *
     * @param int $qte
     *
     * @return Elevation
     */
    public function setQteAppuisFenetre($qte)
    {
        $this->qteAppuisFenetre=$qte;

        return $this;
    }

    /**
     * Set qteMur.
     *
     * @param int $qte
     *
     * @return Elevation
     */
    public function setQteMur($qte)
    {
        $this->qteMur=$qte;

        return $this;
    }

    /**
     * Set qtePoutre.
     *
     * @param int $qte
     *
     * @return Elevation
     */
    public function setQtePoutre($qte)
    {
        $this->qtePoutre=$qte;

        return $this;
    }

    /**
     * Set qteLintaux.
     *
     * @param int $qte
     *
     * @return Elevation
     */
    public function setQteLintaux($qte)
    {
        $this->qteLintaux=$qte;

        return $this;
    }


    /**
     * Set prixLintaux.
     *
     * @param float $prix
     *
     * @return Elevation
     */
    public function setPrixLintaux($prix)
    {
        $this->prixLintaux=$prix;

        return $this;
    }

    /**
     * Set prixTotal.
     *
     * @param float $prix
     *
     * @return Elevation
     */
    public function setPrixTotal($prix)
    {
        $this->prixTotal=$prix;

        return $this;
    }
    /**
     * Set mur.
     *
     * @param int $id
     *
     * @return Elevation
     */
    public function setMur($id)
    {
        $this->mur=$id;

        return $this;
    }

    /**
     * Set appuis.
     *
     * @param int $id
     *
     * @return Elevation
     */
    public function setAppuisFenetre($id)
    {
        $this->appuisFenetre=$id;

        return $this;
    }

    /**
     * Set poutre.
     *
     * @param int $id
     *
     * @return Elevation
     */
    public function setPoutre($id)
    {
        $this->poutre=$id;

        return $this;
    }




    /**
     * Constructor
     */
    public function __construct()
    {
        $this->poutre = new \Doctrine\Common\Collections\ArrayCollection();
        $this->mur = new \Doctrine\Common\Collections\ArrayCollection();
        $this->appuisFenetre = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add poutre.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Poutre $poutre
     *
     * @return Elevation
     */
    public function addPoutre(\HomeConstruct\BuildBundle\Entity\Poutre $poutre)
    {
        $this->poutre[] = $poutre;

        return $this;
    }

    /**
     * Remove poutre.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Poutre $poutre
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePoutre(\HomeConstruct\BuildBundle\Entity\Poutre $poutre)
    {
        return $this->poutre->removeElement($poutre);
    }

    /**
     * Add mur.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Mur $mur
     *
     * @return Elevation
     */
    public function addMur(\HomeConstruct\BuildBundle\Entity\Mur $mur)
    {
        $this->mur[] = $mur;

        return $this;
    }

    /**
     * Remove mur.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Mur $mur
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeMur(\HomeConstruct\BuildBundle\Entity\Mur $mur)
    {
        return $this->mur->removeElement($mur);
    }

    /**
     * Add appuisFenetre.
     *
     * @param \HomeConstruct\BuildBundle\Entity\AppuisFenetre $appuisFenetre
     *
     * @return Elevation
     */
    public function addAppuisFenetre(\HomeConstruct\BuildBundle\Entity\AppuisFenetre $appuisFenetre)
    {
        $this->appuisFenetre[] = $appuisFenetre;

        return $this;
    }

    /**
     * Remove appuisFenetre.
     *
     * @param \HomeConstruct\BuildBundle\Entity\AppuisFenetre $appuisFenetre
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeAppuisFenetre(\HomeConstruct\BuildBundle\Entity\AppuisFenetre $appuisFenetre)
    {
        return $this->appuisFenetre->removeElement($appuisFenetre);
    }

    /**
     * Set grosOeuvre.
     *
     * @param \HomeConstruct\BuildBundle\Entity\GrosOeuvre $grosOeuvre
     *
     * @return Elevation
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
     * Calculer Prix Total des Arbres
     *
     * @ORM\PrePersist
     * @ORM\PostUpdate
     */
    public function calculPrix(){
        $prixMur=null;
        $prixPoutre=null;
        $prixAppuisFenetre=null;
        foreach($this->getMur() as $a){
            $prixMur += $a->calculPrix();
        }
        foreach($this->getPoutre() as $a){
            $prixPoutre += $a->calculPrix();
        }
        foreach($this->getAppuisFenetre() as $a){
            $prixAppuisFenetre += $a->calculPrix();
        }

        $this->setPrixLintaux(10);
        $prixTotalLintaux=$this->prixLintaux*$this->qteLintaux;

        $this->setPrixTotal($prixMur+$prixPoutre+$prixAppuisFenetre+$prixTotalLintaux);
        return $this->getPrixTotal();
    }

    /**
     * @var totaleSurfaceMur
     *
     * @return SurfaceMur
     */
    public function calculSurfaceMur(){
        $total = null;
        foreach($this->getMur() as $mur){
            $total += ($mur->getLongueur() * $mur->getHauteur());
        }
        return $total;
    }

    /**
     * @var totaleSurfaceMurExterieur
     *
     * @return SurfaceMurExterieur
     */
    public function calculSurfaceMurExterieur(){
        $total = null;
        foreach($this->getMur() as $mur){
            if(!$mur->getPiece()){
                $total += ($mur->getLongueur() * $mur->getHauteur());
            }
        }
        return $total;
    }

    /**
     * @var totaleSurfaceMurInterieur
     *
     * @return SurfaceMurInterieur
     */
    public function calculSurfaceMurInterieur(){
        $total = null;
        foreach($this->getMur() as $mur){
            if($mur->getPiece()){
                $total += ($mur->getLongueur() * $mur->getHauteur());
            }
        }
        return $total;
    }

    public function getEntityName(){
        return 'Elevation';
    }
}
