<?php

namespace HomeConstruct\BuildBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use HomeConstruct\BuildBundle\Entity\TypeTerrassement;

class TypeTerrassementFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $types= [
            [
                'nom' => 'Semelle filante (tour de maison)',
                'prix' => 30
            ],
            [
                'nom' => 'Longrines (ou pieux)',
                'prix' => 50
            ]
        ];

        $this->persistStatus($manager, $types);
        $manager->flush();
    }

    public function persistStatus($manager, $types)
    {
        foreach ($types as $type) {
            $typeTerrassement = new TypeTerrassement();
            $typeTerrassement->setNom($type['nom']);
            $typeTerrassement->setPrix($type['prix']);
            $manager->persist($typeTerrassement);
        }
    }
}
