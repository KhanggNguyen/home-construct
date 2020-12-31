<?php

namespace HomeConstruct\BuildBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManagerInterface;


/**
 * Plancher
 *
 * @ORM\Table(name="home_construct_plancher")
 * @ORM\Entity(repositoryClass="HomeConstruct\BuildBundle\Repository\PlancherRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Plancher
{

    protected $em;
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\TypePlancher", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="HomeConstruct\BuildBundle\Entity\GrosOeuvre", cascade={"persist"}, inversedBy="plancher")
     * @ORM\JoinColumn(nullable=false)
     */
    private $grosOeuvre;


    /**
     * @var float
     *
     * @ORM\Column(name="nbM2", type="float", nullable=false)
     */
    private $nbM2;

    /**
     * @var float
     *
     * @ORM\Column(name="longueurPoutrelle", type="float", nullable=false)
     */
    private $longueurPoutrelle;

    /**
     * @var float
     *
     * @ORM\Column(name="longueurEntrevous", type="float", nullable=false)
     */
    private $longueurEntrevous;

    /**
     * @var float
     *
     * @ORM\Column(name="prixTotal", type="float", nullable=true)
     */
    private $prixTotal;

    /**
     * @var int
     *
     * @ORM\Column(name="quantite", type="integer", nullable=true)
     */
    private $quantite;



    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }


    public function setEm(EntityManagerInterface $entityManager):self
    {
        $this->em = $entityManager;
        return $this;
    }

    public function getEm(): ?EntityManagerInterface
    {
        return $this->em;
    }

    /**
     * Get nbM2.
     *
     * @return float
     */
    public function getNbM2()
    {
        return $this->nbM2;
    }

    /**
     * Get longueurEntrevous.
     *
     * @return float
     */
    public function getLongueurEntrevous()
    {
        return $this->longueurEntrevous;
    }

    /**
     * Get longueurPoutrelle.
     *
     * @return float
     */
    public function getLongueurPoutrelle()
    {
        return $this->longueurPoutrelle;
    }

    /**
     * Get prixTotal.
     *
     * @return float
     */
    public function getPrixTotal()
    {
        return $this->prixTotal;
    }

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
     * Get type.
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get grosOeuvre.
     *
     * @return int
     */
    public function getGrosOeuvre()
    {
        return $this->grosOeuvre;
    }

    /**
     * Set type.
     *
     * @param int $id
     *
     * @return Plancher
     */
    public function setType($id)
    {
        $this->type=$id;

        return $this;
    }

    /**
     * Set grosOeuvre.
     *
     * @param int $id
     *
     * @return Plancher
     */

    public function setGrosOeuvre($id)
    {
        $this->grosOeuvre=$id;

        return $this;
    }

    /**
     * Set nbM2.
     *
     * @param float $nb
     *
     * @return Plancher
     */
    public function setNbM2($nb)
    {
        $this->nbM2=$nb;

        return $this;
    }

    /**
     * Set longueurPoutrelle.
     *
     * @param float $longueur
     *
     * @return Plancher
     */
    public function setLongueurPoutrelle($longueur)
    {
        $this->longueurPoutrelle=$longueur;

        return $this;
    }

    /**
     * Set longueurEntrevous.
     *
     * @param float $longueur
     *
     * @return Plancher
     */
    public function setLongueurEntrevous($longueur)
    {
        $this->longueurEntrevous=$longueur;

        return $this;
    }

    /**
     * Set prixTotal.
     *
     * @param float $prix
     *
     * @return Plancher
     */
    public function setPrixTotal($prix)
    {
        $this->prixTotal=$prix;

        return $this;
    }


    /**
     * Set quantite.
     *
     * @param int $quantite
     *
     * @return Plancher
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite.
     *
     * @return int
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * Calculer Prix des Plancher
     *
     * @ORM\PrePersist
     * @ORM\PostUpdate
     */
    public function calculPrix(){
        $prixPlancher = $this->getType()->getPrix()*$this->getNbM2();
        $this->setPrixTotal($prixPlancher);
        return $prixPlancher;
    }

    /**
     * Calculer le prix du gros oeuvre
     *
     * @ORM\PostPersist
     * @ORM\PostUpdate
     */
    public function appelCalculPrixGO(){
        $em=$this->em;
        $grosOeuvre=$this->getGrosOeuvre();
        $grosOeuvre->calculPrix();
        $em->persist($grosOeuvre);
        $em->flush();
        $projet=$grosOeuvre->getProjet();
        $em->persist($projet);
        $em->flush();
    }

    public function getEntityName(){
        return 'Plancher';
    }
}
