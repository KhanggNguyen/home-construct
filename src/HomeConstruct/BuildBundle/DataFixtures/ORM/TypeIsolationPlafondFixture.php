<?php

namespace HomeConstruct\BuildBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use HomeConstruct\BuildBundle\Entity\TypeIsolationPlafond;

class TypeIsolationPlafondFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $types= [
            [
                'nom' => 'Placo phonique',
                'prix' => 40
            ],
            [
                'nom' => 'Liège',
                'prix' => 40
            ],
            [
                'nom' => 'Cellulose polystyrène',
                'prix' => 40
            ]
        ];

        $this->persistStatus($manager, $types);
        $manager->flush();
    }

    public function persistStatus($manager, $types)
    {
        foreach ($types as $type) {
            $typeIsolationPlafond = new TypeIsolationPlafond();
            $typeIsolationPlafond->setNom($type['nom']);
            $typeIsolationPlafond->setPrix($type['prix']);
            $manager->persist($typeIsolationPlafond);
        }
    }
}
