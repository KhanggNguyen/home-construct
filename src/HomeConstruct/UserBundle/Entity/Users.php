<?php

namespace HomeConstruct\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Users
 *
 * @ORM\Table(name="home_construct_user_users")
 * @ORM\Entity(repositoryClass="HomeConstruct\UserBundle\Repository\UsersRepository")
// * @UniqueEntity(fields="usernameCanonical", errorPath="username", message="fos_user.username.already_used", groups={"Default", "SymbaseRegistration", "SymbaseProfile"})
// * @UniqueEntity(fields="emailCanonical", errorPath="email", message="fos_user.email.already_used", groups={"Default", "SymbaseRegistration", "SymbaseProfile"})
 */
class Users extends BaseUser
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=20, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile", type="string", length=20, nullable=true)
     */
    private $mobile;


    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $firstLogin;

    /**
     * @ORM\ManyToMany(targetEntity="HomeConstruct\UserBundle\Entity\Groupe", inversedBy="users", fetch="EXTRA_LAZY")
     * @ORM\JoinTable(name="home_construct_user_users_join_user_group",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="HomeConstruct\BuildBundle\Entity\Projet", inversedBy="users")
     * @ORM\JoinTable(name="home_construct_join_users_projets",
     *   joinColumns={@ORM\JoinColumn(name="users_id", referencedColumnName="id",nullable=true)},
     *   inverseJoinColumns={@ORM\JoinColumn(name="projet_id", referencedColumnName="id",nullable=true)}
     * )
     */
    private $projets;

    /**
     * @var \Doctrine\Common\Collections\Collection|null
     * @ORM\OneToMany(targetEntity="HomeConstruct\BuildBundle\Entity\Projet", mappedBy="createur")
     * @ORM\JoinColumn(nullable=true,onDelete="SET NULL")
     */
    private $projetsCrees;

    /**
     * @var \Doctrine\Common\Collections\Collection|null
     * @ORM\OneToMany(targetEntity="HomeConstruct\BuildBundle\Entity\EtudeSol", mappedBy="createur")
     * @ORM\JoinColumn(nullable=true,onDelete="SET NULL")
     */
    private $etudesSolsCrees;

    /**
     * @var \Doctrine\Common\Collections\Collection|null
     * @ORM\OneToMany(targetEntity="HomeConstruct\BuildBundle\Entity\EtudeSol", mappedBy="modifieur")
     * @ORM\JoinColumn(nullable=true,onDelete="SET NULL")
     */
    private $etudesSolsModifiees;

    /**
     * @var \Doctrine\Common\Collections\Collection|null
     * @ORM\OneToMany(targetEntity="HomeConstruct\BuildBundle\Entity\Excavation", mappedBy="createur")
     * @ORM\JoinColumn(nullable=true,onDelete="SET NULL")
     */
    private $excavationsCrees;

    /**
     * @var \Doctrine\Common\Collections\Collection|null
     * @ORM\OneToMany(targetEntity="HomeConstruct\BuildBundle\Entity\Excavation", mappedBy="modifieur")
     * @ORM\JoinColumn(nullable=true,onDelete="SET NULL")
     */
    private $excavationsModifiees;

    /**
     * @var \Doctrine\Common\Collections\Collection|null
     * @ORM\OneToMany(targetEntity="HomeConstruct\BuildBundle\Entity\PrepaAccesTerrain", mappedBy="createur")
     * @ORM\JoinColumn(nullable=true,onDelete="SET NULL")
     */
    private $prepaAccesTerrainCrees;

    /**
     * @var \Doctrine\Common\Collections\Collection|null
     * @ORM\OneToMany(targetEntity="HomeConstruct\BuildBundle\Entity\PrepaAccesTerrain", mappedBy="modifieur")
     * @ORM\JoinColumn(nullable=true,onDelete="SET NULL")
     */
    private $prepaAccesTerrainModifiees;

    /**
     * @ORM\Column(type="integer", length=6, options={"default":0})
     */
    protected $loginCount = 0;

    public function __construct()
    {
        parent::__construct();
        $this->groups = new ArrayCollection();
    }

    public function __toString()
    {
        $str = "";

        if (!empty($this->firstname)) {
            $str .= $this->firstname . ' ';
        }

        if (!empty($this->name)) {
            $str .= $this->name . ' - ';
        }

        $str .= parent::__toString();

        return $str;
    }

    public function setEmail($email)
    {
        $email = is_null($email) ? '' : $email;
        parent::setEmail($email);
        $this->setUsername($email);

        return $this;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Users
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return Users
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set firstLogin.
     *
     * @param \DateTime|null $firstLogin
     *
     * @return Users
     */
    public function setFirstLogin($firstLogin = null)
    {
        $this->firstLogin = $firstLogin;

        return $this;
    }

    /**
     * Get firstLogin.
     *
     * @return \DateTime|null
     */
    public function getFirstLogin()
    {
        return $this->firstLogin;
    }

    function getEnabled()
    {
        return $this->enabled;
    }

    function setSalt($salt)
    {
        $this->salt = $salt;
    }

    public function setPassword($password)
    {
        if ($password !== null) {
            $this->password = $password;
        }

        return $this;
    }

    public function setGroups(Groupe $groups = null)
    {
        $this->groups = array();

        foreach ($groups as $group) {
            $this->addGroup($group);
        }

        return $this;
    }

    public function setRoles(array $roles = array())
    {
        $this->roles = array();

        foreach ($roles as $role) {
            $this->addRole($role);
        }

        return $this;
    }

    public function hasGroup($name = '')
    {
        return in_array($name, $this->getGroupNames());
    }

    /**
     * Set phone.
     *
     * @param string|null $phone
     *
     * @return Users
     */
    public function setPhone($phone = null)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone.
     *
     * @return string|null
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set mobile.
     *
     * @param string|null $mobile
     *
     * @return Users
     */
    public function setMobile($mobile = null)
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * Get mobile.
     *
     * @return string|null
     */
    public function getMobile()
    {
        return $this->mobile;
    }


    /**
     * Add projet.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Projet $projet
     *
     * @return Users
     */
    public function addProjet(\HomeConstruct\BuildBundle\Entity\Projet $projet)
    {
        $this->projets[] = $projet;

        return $this;
    }

    /**
     * Remove projet.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Projet $projet
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProjet(\HomeConstruct\BuildBundle\Entity\Projet $projet)
    {
        return $this->projets->removeElement($projet);
    }

    /**
     * Get projets.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProjets()
    {
        return $this->projets;
    }

    /**
     * Add projetsCree.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Projet $projetsCree
     *
     * @return Users
     */
    public function addProjetsCree(\HomeConstruct\BuildBundle\Entity\Projet $projetsCree)
    {
        $this->projetsCrees[] = $projetsCree;

        return $this;
    }

    /**
     * Remove projetsCree.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Projet $projetsCree
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProjetsCree(\HomeConstruct\BuildBundle\Entity\Projet $projetsCree)
    {
        return $this->projetsCrees->removeElement($projetsCree);
    }

    /**
     * Get projetsCrees.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProjetsCrees()
    {
        return $this->projetsCrees;
    }

    /**
     * Set loginCount.
     *
     * @param int $loginCount
     *
     * @return Users
     */
    public function setLoginCount($loginCount)
    {
        $this->loginCount = $loginCount;

        return $this;
    }

    /**
     * Get loginCount.
     *
     * @return int
     */
    public function getLoginCount()
    {
        return $this->loginCount;
    }

    /**
     * Add etudesSolsCree.
     *
     * @param \HomeConstruct\BuildBundle\Entity\EtudeSol $etudesSolsCree
     *
     * @return Users
     */
    public function addEtudesSolsCree(\HomeConstruct\BuildBundle\Entity\EtudeSol $etudesSolsCree)
    {
        $this->etudesSolsCrees[] = $etudesSolsCree;

        return $this;
    }

    /**
     * Remove etudesSolsCree.
     *
     * @param \HomeConstruct\BuildBundle\Entity\EtudeSol $etudesSolsCree
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeEtudesSolsCree(\HomeConstruct\BuildBundle\Entity\EtudeSol $etudesSolsCree)
    {
        return $this->etudesSolsCrees->removeElement($etudesSolsCree);
    }

    /**
     * Get etudesSolsCrees.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEtudesSolsCrees()
    {
        return $this->etudesSolsCrees;
    }

    /**
     * Add etudesSolsModifiee.
     *
     * @param \HomeConstruct\BuildBundle\Entity\EtudeSol $etudesSolsModifiee
     *
     * @return Users
     */
    public function addEtudesSolsModifiee(\HomeConstruct\BuildBundle\Entity\EtudeSol $etudesSolsModifiee)
    {
        $this->etudesSolsModifiees[] = $etudesSolsModifiee;

        return $this;
    }

    /**
     * Remove etudesSolsModifiee.
     *
     * @param \HomeConstruct\BuildBundle\Entity\EtudeSol $etudesSolsModifiee
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeEtudesSolsModifiee(\HomeConstruct\BuildBundle\Entity\EtudeSol $etudesSolsModifiee)
    {
        return $this->etudesSolsModifiees->removeElement($etudesSolsModifiee);
    }

    /**
     * Get etudesSolsModifiees.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEtudesSolsModifiees()
    {
        return $this->etudesSolsModifiees;
    }

    /**
     * Add excavationsCree.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Excavation $excavationsCree
     *
     * @return Users
     */
    public function addExcavationsCree(\HomeConstruct\BuildBundle\Entity\Excavation $excavationsCree)
    {
        $this->excavationsCrees[] = $excavationsCree;

        return $this;
    }

    /**
     * Remove excavationsCree.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Excavation $excavationsCree
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeExcavationsCree(\HomeConstruct\BuildBundle\Entity\Excavation $excavationsCree)
    {
        return $this->excavationsCrees->removeElement($excavationsCree);
    }

    /**
     * Get excavationsCrees.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExcavationsCrees()
    {
        return $this->excavationsCrees;
    }


    /**
     * Add excavationsModifiee.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Excavation $excavationsModifiee
     *
     * @return Users
     */
    public function addExcavationsModifiee(\HomeConstruct\BuildBundle\Entity\Excavation $excavationsModifiee)
    {
        $this->excavationsModifiees[] = $excavationsModifiee;

        return $this;
    }

    /**
     * Remove excavationsModifiee.
     *
     * @param \HomeConstruct\BuildBundle\Entity\Excavation $excavationsModifiee
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeExcavationsModifiee(\HomeConstruct\BuildBundle\Entity\Excavation $excavationsModifiee)
    {
        return $this->excavationsModifiees->removeElement($excavationsModifiee);
    }

    /**
     * Get excavationsModifiees.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExcavationsModifiees()
    {
        return $this->excavationsModifiees;
    }

    /**
     * Add prepaAccesTerrainCree.
     *
     * @param \HomeConstruct\BuildBundle\Entity\PrepaAccesTerrain $prepaAccesTerrainCree
     *
     * @return Users
     */
    public function addPrepaAccesTerrainCree(\HomeConstruct\BuildBundle\Entity\PrepaAccesTerrain $prepaAccesTerrainCree)
    {
        $this->prepaAccesTerrainCrees[] = $prepaAccesTerrainCree;

        return $this;
    }

    /**
     * Remove prepaAccesTerrainCree.
     *
     * @param \HomeConstruct\BuildBundle\Entity\PrepaAccesTerrain $prepaAccesTerrainCree
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePrepaAccesTerrainCree(\HomeConstruct\BuildBundle\Entity\PrepaAccesTerrain $prepaAccesTerrainCree)
    {
        return $this->prepaAccesTerrainCrees->removeElement($prepaAccesTerrainCree);
    }

    /**
     * Get prepaAccesTerrainCrees.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPrepaAccesTerrainCrees()
    {
        return $this->prepaAccesTerrainCrees;
    }

    /**
     * Add prepaAccesTerrainModifiee.
     *
     * @param \HomeConstruct\BuildBundle\Entity\PrepaAccesTerrain $prepaAccesTerrainModifiee
     *
     * @return Users
     */
    public function addPrepaAccesTerrainModifiee(\HomeConstruct\BuildBundle\Entity\PrepaAccesTerrain $prepaAccesTerrainModifiee)
    {
        $this->prepaAccesTerrainModifiees[] = $prepaAccesTerrainModifiee;

        return $this;
    }

    /**
     * Remove prepaAccesTerrainModifiee.
     *
     * @param \HomeConstruct\BuildBundle\Entity\PrepaAccesTerrain $prepaAccesTerrainModifiee
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePrepaAccesTerrainModifiee(\HomeConstruct\BuildBundle\Entity\PrepaAccesTerrain $prepaAccesTerrainModifiee)
    {
        return $this->prepaAccesTerrainModifiees->removeElement($prepaAccesTerrainModifiee);
    }

    /**
     * Get prepaAccesTerrainModifiees.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPrepaAccesTerrainModifiees()
    {
        return $this->prepaAccesTerrainModifiees;
    }
}
