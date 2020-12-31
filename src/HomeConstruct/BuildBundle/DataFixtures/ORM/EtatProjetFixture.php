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
use HomeConstruct\BuildBundle\Entity\EtatProjet;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EtatProjetFixture extends AbstractFixture
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
        $types= [
            [
                'nom' => 'En cours'
            ],
            [
                'nom' => 'En attente'
            ],
            [
                'nom' => 'Terminé'
            ],
            [
                'nom' => 'Archivé'
            ],
        ];

        $this->persistStatus($manager, $types);
        $manager->flush();
    }

    public function persistStatus($manager, $types)
    {
        foreach ($types as $type) {
            $etat = new EtatProjet();
            $etat->setNom($type['nom']);
            $manager->persist($etat);
        }
    }
}
