<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Piece
 *
 * @ORM\Table(name="home_construct_piece")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\PieceRepository")
 */
class Piece
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
     * @var string|null
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @var float|null
     *
     * @ORM\Column(name="surface", type="float", precision=255, scale=0, nullable=true)
     */
    private $surface;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="salle_deau", type="boolean", nullable=true)
     */
    private $salleDeau;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="cloison_amovible", type="boolean", nullable=true)
     */
    private $cloisonAmovible;

    /**
     * @var \TypeIsolationPiece
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\TypeIsolationPiece", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $isolation;

    /**
     * @var \Cloison
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\Cloison", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $cloison;

    /**
     * @var \Chauffage
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\Chauffage", cascade={"persist","remove"},inversedBy="piece")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $chauffage;

    /**
     * @var \Ventilation
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\Ventilation", cascade={"persist","remove"},inversedBy="piece")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $ventilation;

    /**
     * @var \Climatisation
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\Climatisation", cascade={"persist","remove"},inversedBy="piece")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $climatisation;

    /**
     * @var \RevetementSol
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\RevetementSol", cascade={"persist","remove"},inversedBy="piece")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $revetementSol;

    /**
     * @var \GrosOeuvre
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\GrosOeuvre", inversedBy="pieces")
     * @ORM\JoinColumn(nullable=false)
     */
    private $grosOeuvre;
    /**
     * @var \Mur
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\Mur", cascade={"persist","remove"}, inversedBy="piece")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $mur;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function getEntityName(){
        return 'Piece';
    }

    /**
     * Set nom.
     *
     * @param string|null $nom
     *
     * @return Piece
     */
    public function setNom($nom = null)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom.
     *
     * @return string|null
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set surface.
     *
     * @param float|null $surface
     *
     * @return Piece
     */
    public function setSurface($surface = null)
    {
        $this->surface = $surface;

        return $this;
    }

    /**
     * Get surface.
     *
     * @return float|null
     */
    public function getSurface()
    {
        return $this->surface;
    }

    /**
     * Set salleDeau.
     *
     * @param bool|null $salleDeau
     *
     * @return Piece
     */
    public function setSalleDeau($salleDeau = null)
    {
        $this->salleDeau = $salleDeau;

        return $this;
    }

    /**
     * Get salleDeau.
     *
     * @return bool|null
     */
    public function getSalleDeau()
    {
        return $this->salleDeau;
    }

    /**
     * Set cloisonAmovible.
     *
     * @param bool|null $cloisonAmovible
     *
     * @return Piece
     */
    public function setCloisonAmovible($cloisonAmovible = null)
    {
        $this->cloisonAmovible = $cloisonAmovible;

        return $this;
    }

    /**
     * Get cloisonAmovible.
     *
     * @return bool|null
     */
    public function getCloisonAmovible()
    {
        return $this->cloisonAmovible;
    }

    /**
     * Set isolation.
     *
     * @param \HomeConstruct\BuildBundle\Entity\TypeIsolationPiece|null $isolation
     *
     * @return Piece
     */
    public function setIsolation(\HomeConstruct\BuildBundle\Entity\TypeIsolationPiece $isolation = null)
    {
        $this->isolation = $isolation;

        return $this;
    }

    /**
     * Get isolation.
     *
     * @return \HomeConstruct\BuildBundle\Entity\TypeIsolationPiece|null
     */
    public function getIsolation()
    {
        return $this->isolation;
    }

    /**
     * Set cloison.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Cloison|null $cloison
     *
     * @return Piece
     */
    public function setCloison(\HomeConstruct\BuildBundle\Entity\Cloison $cloison = null)
    {
        $this->cloison = $cloison;

        return $this;
    }

    /**
     * Get cloison.
     *
     * @return \HomeConstruct\BuildBundle\Entity\Cloison|null
     */
    public function getCloison()
    {
        return $this->cloison;
    }

    /**
     * Set chauffage.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Chauffage|null $chauffage
     *
     * @return Piece
     */
    public function setChauffage(\HomeConstruct\BuildBundle\Entity\Chauffage $chauffage = null)
    {
        $this->chauffage = $chauffage;

        return $this;
    }

    /**
     * Get chauffage.
     *
     * @return \HomeConstruct\BuildBundle\Entity\Chauffage|null
     */
    public function getChauffage()
    {
        return $this->chauffage;
    }

    /**
     * Set ventilation.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Ventilation|null $ventilation
     *
     * @return Piece
     */
    public function setVentilation(\HomeConstruct\BuildBundle\Entity\Ventilation $ventilation = null)
    {
        $this->ventilation = $ventilation;

        return $this;
    }

    /**
     * Get ventilation.
     *
     * @return \HomeConstruct\BuildBundle\Entity\Ventilation|null
     */
    public function getVentilation()
    {
        return $this->ventilation;
    }

    /**
     * Set climatisation.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Climatisation|null $climatisation
     *
     * @return Piece
     */
    public function setClimatisation(\HomeConstruct\BuildBundle\Entity\Climatisation $climatisation = null)
    {
        $this->climatisation = $climatisation;

        return $this;
    }

    /**
     * Get climatisation.
     *
     * @return \HomeConstruct\BuildBundle\Entity\Climatisation|null
     */
    public function getClimatisation()
    {
        return $this->climatisation;
    }

    /**
     * Set revetementSol.
     *
     * @param \HomeConstruct\BuildBundle\Entity\RevetementSol|null $revetementSol
     *
     * @return Piece
     */
    public function setRevetementSol(\HomeConstruct\BuildBundle\Entity\RevetementSol $revetementSol = null)
    {
        $this->revetementSol = $revetementSol;

        return $this;
    }

    /**
     * Get revetementSol.
     *
     * @return \HomeConstruct\BuildBundle\Entity\RevetementSol|null
     */
    public function getRevetementSol()
    {
        return $this->revetementSol;
    }

    /**
     * Set grosOeuvre.
     *
     * @param \HomeConstruct\BuildBundle\Entity\GrosOeuvre $grosOeuvre
     *
     * @return Piece
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
     * Set mur.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Mur|null $mur
     *
     * @return Piece
     */
    public function setMur(\HomeConstruct\BuildBundle\Entity\Mur $mur = null)
    {
        $this->mur = $mur;

        return $this;
    }

    /**
     * Get mur.
     *
     * @return \HomeConstruct\BuildBundle\Entity\Mur|null
     */
    public function getMur()
    {
        return $this->mur;
    }
}
