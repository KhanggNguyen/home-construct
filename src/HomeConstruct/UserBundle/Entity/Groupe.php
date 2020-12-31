<?php

namespace HomeConstruct\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\Group as BaseGroup;

/**
 * Groupe
 *
 * @ORM\Table(name="home_construct_user_groupe")
 * @ORM\Entity(repositoryClass="HomeConstruct\UserBundle\Repository\GroupeRepository")
 */
class Groupe extends BaseGroup
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\ManyToMany(targetEntity="HomeConstruct\UserBundle\Entity\Users", mappedBy="groups")
     *
     */
    protected $users;


    public function __construct($name = '', $roles = array())
    {
        $this->name = $name;
        $this->roles = $roles;
        //$this->roles_global = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
    }

    function getUsers()
    {
        return $this->users;
    }

    /**
     * Add user.
     *
     * @param Users $user
     *
     * @return Group
     */
    public function addUser(Users $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user.
     *
     * @param Users $user
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeUser(Users $user)
    {
        return $this->users->removeElement($user);
    }



    /**
     * Set description.
     *
     * @param string|null $description
     *
     * @return Group
     */
    public function setDescription($description = null)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }
}
