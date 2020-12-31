<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RevetementSolPiece
 *
 * @ORM\Table(name="home_construct_revetement_sol_piece")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\RevetementSolPieceRepository")
 */
class RevetementSolPiece
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
     * @ORM\Column(name="prix_m2", type="float", precision=255, scale=0, nullable=true)
     */
    private $prixM2;

    /**
     * @var \Piece
     *
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\Piece", cascade={"persist"})
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
     * Set nom.
     *
     * @param string|null $nom
     *
     * @return RevetementSolPiece
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
     * Set prixM2.
     *
     * @param float|null $prixM2
     *
     * @return RevetementSolPiece
     */
    public function setPrixM2($prixM2 = null)
    {
        $this->prixM2 = $prixM2;

        return $this;
    }

    /**
     * Get prixM2.
     *
     * @return float|null
     */
    public function getPrixM2()
    {
        return $this->prixM2;
    }

    public function getEntityName(){
        return 'Revetement Sol Piece';
    }

    /**
     * Set piece.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Piece|null $piece
     *
     * @return RevetementSolPiece
     */
    public function setPiece(\HomeConstruct\BuildBundle\Entity\Piece $piece = null)
    {
        $this->piece = $piece;

        return $this;
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
}
