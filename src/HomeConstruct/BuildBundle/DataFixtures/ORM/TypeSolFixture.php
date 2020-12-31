<?php

namespace HomeConstruct\BuildBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use HomeConstruct\BuildBundle\Entity\TypeSol;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TypeSolFixture extends AbstractFixture
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
        return 8;
    }

    public function load(ObjectManager $manager)
    {
        $profondeurSemiProfonde=$this->container
            ->get('doctrine')
            ->getManager('default')
            ->getRepository('HomeConstructBuildBundle:ProfondeurRecommande')
            ->findOneBy(['nom' => "Semi-profonde"]);

        $profondeurSuperficielle=$this->container
            ->get('doctrine')
            ->getManager('default')
            ->getRepository('HomeConstructBuildBundle:ProfondeurRecommande')
            ->findOneBy(['nom' => "Superficielle"]);

        $types= [
            [
                'nom' => 'Argileux',
                'profondeurRecommande' => $profondeurSemiProfonde
            ],
            [
                'nom' => 'Calcaire',
                'profondeurRecommande' => $profondeurSuperficielle
            ],
            [
                'nom' => 'Humifere',
                'profondeurRecommande' => $profondeurSuperficielle
            ],
            [
                'nom' => 'Limoneux',
                'profondeurRecommande' => $profondeurSuperficielle
            ],
            [
                'nom' => 'Sableux',
                'profondeurRecommande' => $profondeurSemiProfonde
            ]
        ];

        $this->persistStatus($manager, $types);
        $manager->flush();
    }

    public function persistStatus($manager, $types)
    {
        foreach ($types as $type) {
            $typeSol = new TypeSol();
            $typeSol->setNom($type['nom']);
            $typeSol->setProfondeurRecommande($type['profondeurRecommande']);
            $manager->persist($typeSol);
        }
    }
}
