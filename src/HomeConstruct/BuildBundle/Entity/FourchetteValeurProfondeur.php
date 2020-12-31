<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="home_construct_fourchette_valeur_profondeur")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\FourchetteValeurProfondeurRepository")
 */
class FourchetteValeurProfondeur
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $minimum;

    /**
     * @ORM\Column(type="float")
     */
    private $maximum;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMinimum(): ?float
    {
        return $this->minimum;
    }

    public function setMinimum(float $minimum): self
    {
        $this->minimum = $minimum;

        return $this;
    }

    public function getMaximum(): ?float
    {
        return $this->maximum;
    }

    public function setMaximum(float $maximum): self
    {
        $this->maximum = $maximum;

        return $this;
    }

    public function getEntityName(){
        return 'Fourchette Valeur Profondeur';
    }
}
