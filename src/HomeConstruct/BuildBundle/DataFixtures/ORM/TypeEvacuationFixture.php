<?php

namespace HomeConstruct\BuildBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use HomeConstruct\BuildBundle\Entity\TypeEvacuation;

class TypeEvacuationFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $types= [
            [
                'nom' => 'Cheminée',
                'prix'=> '1500'
            ],
            [
                'nom' => 'Hotte (cuisine)',
                'prix' => '300'
            ]
        ];

        $this->persistStatus($manager, $types);
        $manager->flush();
    }

    public function persistStatus($manager, $types)
    {
        foreach ($types as $type) {
            $typeEvacuation = new TypeEvacuation();
            $typeEvacuation->setNom($type['nom']);
            $typeEvacuation->setPrix($type['prix']);
            $manager->persist($typeEvacuation);
        }
    }
}
