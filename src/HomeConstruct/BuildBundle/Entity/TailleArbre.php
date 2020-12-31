<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TailleArbre
 *
 * @ORM\Table(name="home_construct_taille_arbre")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\TailleArbreRepository")
 */
class TailleArbre
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
     * @ORM\Column(name="tarifAbattageNettoyageMin", type="float")
     */
    private $tarifAbattageNettoyageMin;

    /**
     * @var float|null
     *
     * @ORM\Column(name="tarifAbattageNettoyageMax", type="float")
     */
    private $tarifAbattageNettoyageMax;

    /**
     * @var float|null
     *
     * @ORM\Column(name="tarifDessouchageMin", type="float")
     */
    private $tarifDessouchageMin;

    /**
     * @var float|null
     *
     * @ORM\Column(name="tarifDessouchageMax", type="float")
     */
    private $tarifDessouchageMax;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float")
     *
     */
    private $prix;

    /**
     * @var integer
     *
     * @ORM\Column(name="taille", type="integer")
     *
     */
    private $taille;

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
     * @return TailleArbre
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

    public function __toString(){
        // to show the name of the Category in the select
        return $this->nom;
        // to show the id of the Category in the select
        // return $this->id;
    }

    /**
     * Set tarifAbattageNettoyageMin.
     *
     * @param float $tarifAbattageNettoyageMin
     *
     * @return TailleArbre
     */
    public function setTarifAbattageNettoyageMin($tarifAbattageNettoyageMin)
    {
        $this->tarifAbattageNettoyageMin = $tarifAbattageNettoyageMin;

        return $this;
    }

    /**
     * Get tarifAbattageNettoyageMin.
     *
     * @return float
     */
    public function getTarifAbattageNettoyageMin()
    {
        return $this->tarifAbattageNettoyageMin;
    }

    /**
     * Set tarifAbattageNettoyageMax.
     *
     * @param float $tarifAbattageNettoyageMax
     *
     * @return TailleArbre
     */
    public function setTarifAbattageNettoyageMax($tarifAbattageNettoyageMax)
    {
        $this->tarifAbattageNettoyageMax = $tarifAbattageNettoyageMax;

        return $this;
    }

    /**
     * Get tarifAbattageNettoyageMax.
     *
     * @return float
     */
    public function getTarifAbattageNettoyageMax()
    {
        return $this->tarifAbattageNettoyageMax;
    }

    /**
     * Set tarifDessouchageMin.
     *
     * @param float $tarifDessouchageMin
     *
     * @return TailleArbre
     */
    public function setTarifDessouchageMin($tarifDessouchageMin)
    {
        $this->tarifDessouchageMin = $tarifDessouchageMin;

        return $this;
    }

    /**
     * Get tarifDessouchageMin.
     *
     * @return float
     */
    public function getTarifDessouchageMin()
    {
        return $this->tarifDessouchageMin;
    }

    /**
     * Set tarifDessouchageMax.
     *
     * @param float $tarifDessouchageMax
     *
     * @return TailleArbre
     */
    public function setTarifDessouchageMax($tarifDessouchageMax)
    {
        $this->tarifDessouchageMax = $tarifDessouchageMax;

        return $this;
    }

    /**
     * Get tarifDessouchageMax.
     *
     * @return float
     */
    public function getTarifDessouchageMax()
    {
        return $this->tarifDessouchageMax;
    }

    /**
     * Set prix.
     *
     * @param float $prix
     *
     * @return TailleArbre
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
     * Set taille.
     *
     * @param int $taille
     *
     * @return TailleArbre
     */
    public function setTaille($taille)
    {
        $this->taille = $taille;

        return $this;
    }

    /**
     * Get taille.
     *
     * @return int
     */
    public function getTaille()
    {
        return $this->taille;
    }

    public function getEntityName(){
        return 'Taille Arbre';
    }
}
