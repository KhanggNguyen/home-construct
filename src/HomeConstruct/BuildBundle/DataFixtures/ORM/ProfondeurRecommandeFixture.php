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
use HomeConstruct\BuildBundle\Entity\ProfondeurRecommande;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ProfondeurRecommandeFixture extends AbstractFixture
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
        return 7;
    }

    public function load(ObjectManager $manager)
    {

        $fourchetteSemiProfonde =  $this->container
            ->get('doctrine')
            ->getManager('default')
            ->getRepository('HomeConstructBuildBundle:FourchetteValeurProfondeur')
            ->findOneBy(['minimum' => 3.0]);
        $profondeur1 = new ProfondeurRecommande();
        $profondeur1->setFourchetteValeur($fourchetteSemiProfonde);
        $profondeur1->setNom('Semi-profonde');
        $manager->persist($profondeur1);

        $fourchetteSuperficielle =  $this->container
            ->get('doctrine')
            ->getManager('default')
            ->getRepository('HomeConstructBuildBundle:FourchetteValeurProfondeur')
            ->findOneBy(['maximum' => 1]);
        $profondeur2 = new ProfondeurRecommande();
        $profondeur2->setFourchetteValeur($fourchetteSuperficielle);
        $profondeur2->setNom('Superficielle');
        $manager->persist($profondeur2);

        $manager->flush();
    }
}
