<?php

namespace HomeConstruct\BuildBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use HomeConstruct\BuildBundle\Entity\TypeChauffage;

class TypeChauffageFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $types= [
            [
                'nom' => 'Fioul',
                'prix' => 500
            ],
            [
                'nom' => 'Electrique',
                'prix' => 6000
            ],
            [
                'nom' => 'Gaz',
                'prix' => 1500
            ],
        ];

        $this->persistStatus($manager, $types);
        $manager->flush();
    }

    public function persistStatus($manager, $types)
    {
        foreach ($types as $type) {
            $climatisation = new TypeChauffage();
            $climatisation->setNom($type['nom']);
            $climatisation->setPrix($type['prix']);

            $manager->persist($climatisation);
        }
    }
}
