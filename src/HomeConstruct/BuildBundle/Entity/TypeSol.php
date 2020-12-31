<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeSol
 *
 * @ORM\Table(name="home_construct_type_sol")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\TypeSolRepository")
 */
class TypeSol
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
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\ProfondeurRecommande")
     * @ORM\JoinColumn(nullable=false)
     */
    private $profondeurRecommande;


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
     * @return TypeSol
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

    public function getProfondeurRecommande(): ?ProfondeurRecommande
    {
        return $this->profondeurRecommande;
    }

    public function setProfondeurRecommande(?ProfondeurRecommande $profondeurRecommande): self
    {
        $this->profondeurRecommande = $profondeurRecommande;

        return $this;
    }

    public function getEntityName(){
        return 'Type Sol';
    }
}
