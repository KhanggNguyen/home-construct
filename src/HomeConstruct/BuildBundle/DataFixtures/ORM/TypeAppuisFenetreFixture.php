<?php

namespace HomeConstruct\BuildBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use HomeConstruct\BuildBundle\Entity\TypeAppuisFenetre;

class TypeAppuisFenetreFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $types= [
            [
                'nom' => 'PVC',
                'prix' => 500
            ],
            [
                'nom' => 'Bois/Aluminium',
                'prix' => 1000
            ],
            [
                'nom' => 'Aluminium',
                'prix' => 450
            ],
            [
                'nom' => 'Bois',
                'prix' => 550
            ]
        ];

        $this->persistStatus($manager, $types);
        $manager->flush();
    }

    public function persistStatus($manager, $types)
    {
        foreach ($types as $type) {
            $typeAppuisFenetre = new TypeAppuisFenetre();
            $typeAppuisFenetre->setNom($type['nom']);
            $typeAppuisFenetre->setPrix($type['prix']);
            $manager->persist($typeAppuisFenetre);
        }
    }
}
