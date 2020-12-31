<?php
namespace HomeConstruct\BuildBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
/**
 * Terrassement
 *
 * @ORM\Table(name="home_construct_terrassement")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\TerrassementRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Terrassement
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
     * @var longueur
     *
     * @ORM\Column(name="longueur", type="float")
     */
    private $longueur;

    /**
     * @var float
     *
     * @ORM\Column(name="largeur", type="float")
     */
    private $largeur;

    /**
     * @var float
     *
     * @ORM\Column(name="profondeur", type="float")
     */
    private $profondeur;

    /**
     * @var float
     *
     * @ORM\Column(name="altimetrie", type="float")
     */
    private $altimetrie;

    /**
     * @var \prepaAccesTerrain
     *
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\PrepaAccesTerrain", inversedBy="terrassements", cascade={"persist"})
     */
    private $prepaAccesTerrain;
    /**
     * @var \EtudeSol
     *
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\EtudeSol", cascade={"persist","remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $etudeSol;

    /**
     * @var \Travaux
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\Travaux", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $travaux;
    /**
     * @var \Evacuation
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\EvacuationTerrassement", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $evacuation;

    /**
     * @var float
     *
     * @ORM\Column(name="prixTotal", type="float", nullable=true)
     */
    private $prixTotal;

    /**
     * Constructor
     */
    public function __construct()
    {
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
     * Set profondeur.
     *
     * @param float $profondeur
     *
     * @return Terrassement
     */
    public function setProfondeur($profondeur)
    {
        $this->profondeur = $profondeur;
        return $this;
    }
    /**
     * Get profondeur.
     *
     * @return float
     */
    public function getProfondeur()
    {
        return $this->profondeur;
    }
    /**
     * Set altimetrie.
     *
     * @param float $altimetrie
     *
     * @return Terrassement
     */
    public function setAltimetrie($altimetrie)
    {
        $this->altimetrie = $altimetrie;
        return $this;
    }
    /**
     * Get altimetrie.
     *
     * @return float
     */
    public function getAltimetrie()
    {
        return $this->altimetrie;
    }
    /**
     * Set etudeSol.
     *
     * @param \HomeConstruct\BuildBundle\Entity\EtudeSol|null $etudeSol
     *
     * @return Terrassement
     */
    public function setEtudeSol(\HomeConstruct\BuildBundle\Entity\EtudeSol $etudeSol = null)
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
     * @return Terrassement
     */
    public function setPrepaAccesTerrain(\HomeConstruct\BuildBundle\Entity\PrepaAccesTerrain $prepaAccesTerrain = null)
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
     * Set travaux.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Travaux $travaux
     *
     * @return Terrassement
     */
    public function setTravaux(\HomeConstruct\BuildBundle\Entity\Travaux $travaux)
    {
        $this->travaux = $travaux;
        return $this;
    }
    /**
     * Get travaux.
     *
     * @return \HomeConstruct\BuildBundle\Entity\Travaux
     */
    public function getTravaux()
    {
        return $this->travaux;
    }
    /**
     * Set evacuation.
     *
     * @param \HomeConstruct\BuildBundle\Entity\EvacuationTerrassement $evacuation
     *
     * @return Terrassement
     */
    public function setEvacuation(\HomeConstruct\BuildBundle\Entity\EvacuationTerrassement $evacuation)
    {
        $this->evacuation = $evacuation;
        return $this;
    }
    /**
     * Get evacuation.
     *
     * @return \HomeConstruct\BuildBundle\Entity\EvacuationTerrassement
     */
    public function getEvacuation()
    {
        return $this->evacuation;
    }

    /**
     * Set prixTotal.
     *
     * @param float $prixTotal
     *
     * @return Terrassement
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
     * Calculer la prix total des terrassement
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function calculPrix(){
        //calculer le prix de terrassement
        if($this->getAltimetrie() != 0 or $this->getAltimetrie() != null){
            $prixTravaux = $this->getTravaux()->getPrix() * $this->getLongueur() * $this->getLargeur() * $this->getAltimetrie();
            $prixEvacuation = $this->getEvacuation()->getPrix() * $this->getLongueur() * $this->getLargeur() * $this->getAltimetrie();
            $prixTerrassement = $prixTravaux + $prixEvacuation;
        }else{
            $prixTravaux = $this->getTravaux()->getPrix() * $this->getLongueur() * $this->getLargeur() * $this->getProfondeur();
            $prixEvacuation = $this->getEvacuation()->getPrix() * $this->getLongueur() * $this->getLargeur() * $this->getProfondeur();
            $prixTerrassement = $prixTravaux + $prixEvacuation;
        }
        $this->setPrixTotal($prixTerrassement);
        return $prixTerrassement;
    }

    public function getEntityName(){
        return 'Terrassement';
    }

    /**
     * Set longueur.
     *
     * @param float $longueur
     *
     * @return Terrassement
     */
    public function setLongueur($longueur)
    {
        $this->longueur = $longueur;

        return $this;
    }

    /**
     * Get longueur.
     *
     * @return float
     */
    public function getLongueur()
    {
        return $this->longueur;
    }

    /**
     * Set largeur.
     *
     * @param float $largeur
     *
     * @return Terrassement
     */
    public function setLargeur($largeur)
    {
        $this->largeur = $largeur;

        return $this;
    }

    /**
     * Get largeur.
     *
     * @return float
     */
    public function getLargeur()
    {
        return $this->largeur;
    }
}
