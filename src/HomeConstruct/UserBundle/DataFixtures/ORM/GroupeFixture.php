<?php

namespace HomeConstruct\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use HomeConstruct\UserBundle\Entity\Groupe;
use HomeConstruct\UserBundle\Entity\Permission;
use HomeConstruct\UserBundle\Entity\Role;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class GroupeFixture extends AbstractFixture
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
        return 4;
    }

    public function load(ObjectManager $manager)
    {

        $groupeAdmin = new Groupe();
        $groupeAdmin->setName('SUPER ADMIN');
        $groupeAdmin->setDescription('Super Administrateur - aucune restriction');

        $manager->persist($groupeAdmin);

        $groupeClient = new Groupe();
        $groupeClient->setName('CLIENT');
        $groupeClient->setDescription('Client');

        $manager->persist($groupeClient);

        $groupePro = new Groupe();
        $groupePro->setName('PROFESSIONNEL');
        $groupePro->setDescription('Professionnel');

        $manager->persist($groupePro);
        $manager->flush();

        $this->addReference('groupe-admin', $groupeAdmin);
        $this->addReference('groupe-client', $groupeClient);
        $this->addReference('groupe-pro', $groupePro);

    }
}