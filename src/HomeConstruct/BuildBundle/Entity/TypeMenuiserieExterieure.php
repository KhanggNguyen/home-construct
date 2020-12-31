<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeMenuiserieExterieure
 *
 * @ORM\Table(name="home_construct_type_menuiserie_exterieure")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\TypeMenuiserieExterieureRepository")
 */
class TypeMenuiserieExterieure
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
     * @return MateriauxMenuiserieExterieure
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

    public function getEntityName(){
        return 'Type Menuiserie Exterieure';
    }

}
