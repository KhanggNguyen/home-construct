<?php

namespace HomeConstruct\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use HomeConstruct\UserBundle\Entity\Groupe;
use HomeConstruct\UserBundle\Entity\Users;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class UsersFixture extends AbstractFixture
    implements OrderedFixtureInterface, ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Sets the container.
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Get the order of this fixture
     * @return integer
     */
    public function getOrder()
    {
        return 5;
    }

    public function load(ObjectManager $manager)
    {


        $adminUser = new Users();
        $adminUser
            ->setUsername('admin')
            ->setEmail('killian.weidner@etu.umontpellier.fr')
            ->setName('Developpeur')
            ->setFirstname('Super')
            ->setEnabled(true)
            ->setPlainPassword('3t!cadmin')
            ->setPassword('3t!cadmin')
            ->setFirstLogin(\DateTime::createFromFormat('j-M-Y', '01-Feb-2000'))
            ->addGroup($this->getReference('groupe-admin'))
            ->addRole('ROLE_SUPER_ADMIN'); // Pas obligé, vu que son groupe l'a, mais c'est la

        // définition de ce compte
        $manager->persist($adminUser);
        $manager->flush();

    }
}