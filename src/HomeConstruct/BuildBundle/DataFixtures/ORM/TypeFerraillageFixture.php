<?php

namespace HomeConstruct\BuildBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use HomeConstruct\BuildBundle\Entity\TypeFerraillage;

class TypeFerraillageFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $types= [
            [
                'nom' => '15/35'
            ],
            [
                'nom' => '6 fois tour de 8'
            ]
        ];

        $this->persistStatus($manager, $types);
        $manager->flush();
    }

    public function persistStatus($manager, $types)
    {
        foreach ($types as $type) {
            $ferraillage = new TypeFerraillage();
            $ferraillage->setNom($type['nom']);
            $manager->persist($ferraillage);
        }
    }
}
