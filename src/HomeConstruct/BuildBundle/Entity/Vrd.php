<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Vrd
 * @ORM\Table(name="home_construct_vrd")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\VrdRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Vrd
{

    protected $em;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\ReseauEauPotable", cascade={"persist", "remove"})
     */
    private $reseauEauPotable;

    /**
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\ReseauEauUsee", cascade={"persist", "remove"})
     */
    private $reseauEauUsee;

    /**
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\ReseauElectrique", cascade={"persist", "remove"})
     */
    private $reseauElectrique;

    /**
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\ReseauGazNaturel")
     * @ORM\JoinColumn(nullable=true)
     */
    private $reseauGazNaturel;

    /**
     * @ORM\Column(type="float")
     */
    private $prixReseauTelephonique;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $prixTotal;

    /**
     * @var \GrosOeuvre
     * @ORM\OneToOne(targetEntity="HomeConstruct\BuildBundle\Entity\GrosOeuvre", inversedBy="vrd"))
     * @ORM\JoinColumn(nullable=false)
     */
    private $grosOeuvre;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function setEm(?EntityManagerInterface $entityManager):self
    {
        $this->em = $entityManager;
        return $this;
    }

    public function getEm(): ?EntityManagerInterface
    {
        return $this->em;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReseauEauUsee(): ?ReseauEauUsee
    {
        return $this->reseauEauUsee;
    }

    public function setReseauEauUsee(?ReseauEauUsee $reseauEauUsee): self
    {
        $this->reseauEauUsee = $reseauEauUsee;

        return $this;
    }

    public function getReseauEauPotable(): ?ReseauEauPotable
    {
        return $this->reseauEauPotable;
    }

    public function setReseauEauPotable(?ReseauEauPotable $reseauEauPotable): self
    {
        $this->reseauEauPotable = $reseauEauPotable;

        return $this;
    }

    public function getReseauElectrique(): ?ReseauElectrique
    {
        return $this->reseauElectrique;
    }

    public function setReseauElectrique(?ReseauElectrique $reseauElectrique): self
    {
        $this->reseauElectrique = $reseauElectrique;

        return $this;
    }

    public function getPrixReseauTelephonique(): ?float
    {
        return $this->prixReseauTelephonique;
    }

    public function setPrixReseauTelephonique(float $prixReseauTelephonique): self
    {
        $this->prixReseauTelephonique = $prixReseauTelephonique;

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

    public function getGrosOeuvre(): ?GrosOeuvre
    {
        return $this->grosOeuvre;
    }

    public function setGrosOeuvre(GrosOeuvre $grosOeuvre): self
    {
        $this->grosOeuvre = $grosOeuvre;

        return $this;
    }

    /**
     * Calcule le prix total et le set au prix de la vrd.
     * Calcule également les prix de chaque reseaux et les sets aux attributs prix respectifs.
     *
     */
    public function calculPrix()
    {
        $prixReseauEauPotable=900.0;
        $prixReseauEauUsee=300.0;
        $prixReseauElectrique=450.0;
        $prixReseauTelephonique=$this->getPrixReseauTelephonique();
        $prixFosseSeptique=1500.0;
        $prixPompeRelevage=1000.0;
        $prixMicroStation=5000.0;
        $prixEtudeHydro=500.0;
        if($this->getGrosOeuvre()->getInformationBase()){
            if($this->getGrosOeuvre()->getInformationBase()->getSurfaceTotale()){
                $surface=$this->getGrosOeuvre()->getInformationBase()->getSurfaceTotale();
                $prixReseauEauPotable+=$surface*35;
                $prixReseauEauUsee*=$surface;
            }
        }
        $reseauEauPotable=$this->getReseauEauPotable();
        $reseauEauPotable->setPrix($prixReseauEauPotable);

        $reseauEauUsee=$this->getReseauEauUsee();
        if($reseauEauUsee->getFosseSeptique()){
            $prixReseauEauUsee+=$prixFosseSeptique;
        }
        if($reseauEauUsee->getPompeRelevage()){
            $prixReseauEauUsee+=$prixPompeRelevage;
        }
        if($reseauEauUsee->getMicroStation()){
            $prixReseauEauUsee+=$prixMicroStation;
        }
        if($reseauEauUsee->getEtudeHydro()){
            $prixReseauEauUsee+=$prixEtudeHydro;
        }
        $reseauEauUsee->setPrix($prixReseauEauUsee);

        $reseauElectrique=$this->getReseauElectrique();
        $reseauElectrique->setPrix($prixReseauElectrique);

        if($this->getReseauGazNaturel()){
            $prixReseauGazNaturel=$this->getReseauGazNaturel()->getPrixForfait();
        }else{
            $prixReseauGazNaturel=null;
        }
        $this->em->persist($reseauEauPotable);
        $this->em->persist($reseauEauUsee);
        $this->em->persist($reseauElectrique);
        $this->setPrixTotal($prixReseauEauPotable+$prixReseauEauUsee+$prixReseauElectrique+$prixReseauGazNaturel+$prixReseauTelephonique);
        $this->em->persist($this);
        $this->em->flush();
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

    /**
     * Calcule le prix total du gros oeuvre apres une suppression.
     *
     * @ORM\PostRemove
     */
    public function removePrix()
    {
        $grosOeuvre=$this->getGrosOeuvre();
        $grosOeuvre->calculPrix();
    }


    public function getEntityName(){
        return 'Vrd';
    }

    public function getReseauGazNaturel(): ?ReseauGazNaturel
    {
        return $this->reseauGazNaturel;
    }

    public function setReseauGazNaturel(?ReseauGazNaturel $reseauGazNaturel): self
    {
        $this->reseauGazNaturel = $reseauGazNaturel;

        return $this;
    }

}
