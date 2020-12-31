<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeChauffage
 *
 * @ORM\Table(name="home_construct_type_chauffage")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\TypeChauffageRepository")
 */
class TypeChauffage
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
     * Set nom.
     *
     * @param string|null $nom
     *
     * @return TypeChauffage
     */
    public function setNom($nom = null)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Set prix.
     *
     * @param float|null $prix
     *
     * @return TypeChauffage
     */
    public function setPrix($prix = null)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix.
     *
     * @return float|null
     */
    public function getPrix()
    {
        return $this->prix;
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

    public function getEntityName(){
        return 'Type Chauffage';
    }
}
