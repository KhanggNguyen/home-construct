<?php

namespace HomeConstruct\BuildBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use HomeConstruct\BuildBundle\Entity\EvacuationTerrassement;

class EvacuationTerrassementFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $types= [
            [
                'nom' => 'Terre',
                'prix'=> '8'
            ],
            [
                'nom' => 'Gravats',
                'prix' => '15'
            ],
            [
                'nom' => 'Dechets',
                'prix' => '25'
            ]
        ];

        $this->persistStatus($manager, $types);
        $manager->flush();
    }

    public function persistStatus($manager, $types)
    {
        foreach ($types as $type) {
            $evacuationTerrassement = new EvacuationTerrassement();
            $evacuationTerrassement->setNom($type['nom']);
            $evacuationTerrassement->setPrix($type['prix']);
            $manager->persist($evacuationTerrassement);
        }
    }
}
