<?php
/**
 * Created by PhpStorm.
 * User: kweidner
 * Date: 26/02/2019
 * Time: 12:09
 */

namespace HomeConstruct\BuildBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use HomeConstruct\BuildBundle\Entity\Projet;
use HomeConstruct\UserBundle\Entity\Users;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ProjetFixture extends AbstractFixture
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
        return 6;
    }

    public function load(ObjectManager $manager)
    {
        $projet = new Projet();
        $projet->setNom('Mon premier projet');
        $userDev =  $this->container
            ->get('doctrine')
            ->getManager('default')
            ->getRepository('HomeConstructUserBundle:Users')
            ->findOneBy(['email' => 'killian.weidner@etu.umontpellier.fr']);
        $etat=$this->container
            ->get('doctrine')
            ->getManager('default')
            ->getRepository('HomeConstructBuildBundle:EtatProjet')
            ->findOneBy(['nom' => 'En attente']);
        $projet->addUser($userDev);
        $projet->setEtat($etat);
        $projet->setCreateur($userDev);
        $userDev->addProjet($projet);
        $date = date('d/m/Y H:i');
        $date = date_create_from_format('d/m/Y H:i', $date);
        $projet->setDateCreation($date);
        $manager->persist($projet);
        $manager->persist($userDev);
        $manager->flush();
    }
}
