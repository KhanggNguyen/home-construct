<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mur
 *
 * @ORM\Table(name="home_construct_mur")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\MurRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Mur
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
     * @var string|null
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @var float|null
     *
     * @ORM\Column(name="longueur", type="float", precision=255, scale=0, nullable=true)
     */
    private $longueur;


    /**
     * @var float|null
     *
     * @ORM\Column(name="hauteur", type="float", precision=255, scale=0, nullable=true)
     */
    private $hauteur;

    /**
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\TypeMateriauxMur", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\Elevation", cascade={"persist"},inversedBy="mur")
     * @ORM\JoinColumn(nullable=false)
     */
    private $elevation;

    /**
     * @var \Piece
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\Piece", cascade={"persist"},mappedBy="mur")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $piece;


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
     * Get type.
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set nom.
     *
     * @param string|null $nom
     *
     * @return Mur
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
     * Get elevation.
     *
     * @return int
     */
    public function getElevation()
    {
        return $this->elevation;
    }
    /**
     * Get piece.
     *
     * @return \HomeConstruct\BuildBundle\Entity\Piece|null
     */
    public function getPiece()
    {
        return $this->piece;
    }

    /**
     * Get hauteur.
     *
     * @return float
     */
    public function getHauteur()
    {
        return $this->hauteur;
    }

    /**
     * Get largeur.
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
     * @return Mur
     */
    public function setLongueur($longueur)
    {
        $this->longueur=$longueur;

        return $this;
    }

    /**
     * Set hauteur.
     *
     * @param float $hauteur
     *
     * @return Mur
     */
    public function setHauteur($hauteur)
    {
        $this->hauteur=$hauteur;

        return $this;
    }

    /**
     * Set type.
     *
     * @param int $id
     *
     * @return Mur
     */
    public function setType($id)
    {
        $this->type=$id;

        return $this;
    }
    /**
     * Set piece.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Piece|null $piece
     *
     * @return Mur
     */
    public function setPiece(\HomeConstruct\BuildBundle\Entity\Piece $piece = null)
    {
        $this->piece = $piece;

        return $this;
    }

    /**
     * Set elevation.
     *
     * @param int $id
     *
     * @return Mur
     */

    public function setElevation($id)
    {
        $this->elevation=$id;

        return $this;
    }

    /**
     * Calculer Prix des Murs
     *
     * @ORM\PrePersist
     * @ORM\PostUpdate
     */
    public function calculPrix(){
        $prixMur = $this->getType()->getPrix() * $this->getLongueur()*$this->getHauteur();
        return $prixMur;

    }

    public function getEntityName(){
        return 'Mur';
    }


}
