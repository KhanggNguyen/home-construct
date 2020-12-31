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
use HomeConstruct\BuildBundle\Entity\FourchetteValeurProfondeur;
use HomeConstruct\UserBundle\Entity\Users;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FourchetteValeurProfondeurFixture extends AbstractFixture
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

        $fourchette1 = new FourchetteValeurProfondeur();
        $fourchette1->setMinimum(3.0);
        $fourchette1->setMaximum(6.0);
        $manager->persist($fourchette1);

        $fourchette2 = new FourchetteValeurProfondeur();
        $fourchette2->setMinimum(0.1);
        $fourchette2->setMaximum(1.0);
        $manager->persist($fourchette2);

        $manager->flush();
    }
}
