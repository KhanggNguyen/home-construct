<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TarifArbre
 *
 * @ORM\Table(name="home_construct_tarif_arbre")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\TarifArbreRepository")
 */
class TarifArbre
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
     * @var int
     *
     * @ORM\Column(name="nombreArbre", type="integer")
     */
    private $nombreArbre;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float")
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
     * Set nombreArbre.
     *
     * @param int $nombreArbre
     *
     * @return TarifArbre
     */
    public function setNombreArbre($nombreArbre)
    {
        $this->nombreArbre = $nombreArbre;

        return $this;
    }

    /**
     * Get nombreArbre.
     *
     * @return int
     */
    public function getNombreArbre()
    {
        return $this->nombreArbre;
    }

    /**
     * Set prix.
     *
     * @param float $prix
     *
     * @return TarifArbre
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

    public function getEntityName(){
        return 'Tarif Arbre';
    }
}
