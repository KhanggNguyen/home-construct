<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Soubassement
 *
 * @ORM\Table(name="home_construct_soubassement")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\SoubassementRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Soubassement
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\TypeSoubassement")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $prixTotal;

    /**
     * @var \GrosOeuvre
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\GrosOeuvre", inversedBy="soubassement"))
     * @ORM\JoinColumn(nullable=false)
     */
    private $grosOeuvre;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?TypeSoubassement
    {
        return $this->type;
    }

    public function setType(?TypeSoubassement $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getGrosOeuvre(): ?GrosOeuvre
    {
        return $this->grosOeuvre;
    }

    public function setGrosOeuvre(?GrosOeuvre $grosOeuvre): self
    {
        $this->grosOeuvre = $grosOeuvre;

        return $this;
    }

    public function getPrixTotal(): ?float
    {
        return $this->prixTotal;
    }

    public function setPrixTotal(?float $prixTotal): self
    {
        $this->prixTotal = $prixTotal;

        return $this;
    }

    /**
     * Calcule le prix total et le set au prix du soubassement.
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function calculPrix()
    {
        if($this->getGrosOeuvre()->getInformationBase() && $this->getGrosOeuvre()->getEtudeSol()){
            $nbM2=$this->getGrosOeuvre()->getInformationBase()->getSurfaceTotale();
            $profondeur=$this->getGrosOeuvre()->getEtudeSol()->getProfondeurChoisie();
            $prixType=$this->getType()->getPrixForfait();
            $prix=$prixType*($nbM2*$profondeur);
        }else{
            $prix=null;
        }
        $this->setPrixTotal($prix);
        return $prix;
    }

    /**
     * Calculer le prix du gros oeuvre
     *
     * @ORM\PostPersist
     * @ORM\PostUpdate
     */
    public function appelCalculPrixGO(){
        $grosOeuvre=$this->getGrosOeuvre();
        $grosOeuvre->calculPrix();
    }

    public function getEntityName(){
        return 'Soubassement';
    }
}
