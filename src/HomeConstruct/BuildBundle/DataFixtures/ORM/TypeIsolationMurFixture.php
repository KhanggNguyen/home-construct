<?php

namespace HomeConstruct\BuildBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use HomeConstruct\BuildBundle\Entity\TypeIsolationMur;

class TypeIsolationMurFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $types= [
            [
                'nom' => 'Laine',
                'prix' => 25
            ],
            [
                'nom' => 'Mousse',
                'prix' => 25
            ],
            [
                'nom' => 'Liege',
                'prix' => 25
            ]
        ];

        $this->persistStatus($manager, $types);
        $manager->flush();
    }

    public function persistStatus($manager, $types)
    {
        foreach ($types as $type) {
            $typeIsolationMur = new TypeIsolationMur();
            $typeIsolationMur->setNom($type['nom']);
            $typeIsolationMur->setPrix($type['prix']);
            $manager->persist($typeIsolationMur);
        }
    }
}
