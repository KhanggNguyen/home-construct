<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeSoubassement
 *
 * @ORM\Table(name="home_construct_type_soubassement")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\TypeSoubassementRepository")
 */
class TypeSoubassement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="float")
     */
    private $prixForfait;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrixForfait(): ?float
    {
        return $this->prixForfait;
    }

    public function setPrixForfait(float $prixForfait): self
    {
        $this->prixForfait = $prixForfait;

        return $this;
    }

    public function getEntityName(){
        return 'Type Soubassement';
    }
}
