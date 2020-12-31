<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cloison
 *
 * @ORM\Table(name="home_construct_cloison")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\CloisonRepository")
 */
class Cloison
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
     * @return Cloison
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
     * @return Cloison
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
        return 'Cloison';
    }

}
