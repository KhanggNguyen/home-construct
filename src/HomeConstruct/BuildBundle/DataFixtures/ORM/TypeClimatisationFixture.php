<?php

namespace HomeConstruct\BuildBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use HomeConstruct\BuildBundle\Entity\TypeClimatisation;

class TypeClimatisationFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $types= [
            [
                'nom' => 'Mobile',
                'prix' => 500
            ],
            [
                'nom' => 'Murale',
                'prix' => 300
            ],
            [
                'nom' => 'Cassette',
                'prix' => 100
            ]
        ];

        $this->persistStatus($manager, $types);
        $manager->flush();
    }

    public function persistStatus($manager, $types)
    {
        foreach ($types as $type) {
            $climatisation = new TypeClimatisation();
            $climatisation->setNom($type['nom']);
            $climatisation->setPrix($type['prix']);

            $manager->persist($climatisation);
        }
    }
}
