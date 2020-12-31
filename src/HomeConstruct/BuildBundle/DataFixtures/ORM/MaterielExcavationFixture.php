<?php

namespace HomeConstruct\BuildBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use HomeConstruct\BuildBundle\Entity\MaterielExcavation;

class MaterielExcavationFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $types= [
            [
                'nom' => 'Camion'
            ],
            [
                'nom' => 'Pelle mécanique'
            ],
            [
                'nom' => 'Tractopelle'
            ]
        ];

        $this->persistStatus($manager, $types);
        $manager->flush();
    }

    public function persistStatus($manager, $types)
    {
        foreach ($types as $type) {
            $materielExcavation = new MaterielExcavation();
            $materielExcavation->setNom($type['nom']);
            $manager->persist($materielExcavation);
        }
    }
}
