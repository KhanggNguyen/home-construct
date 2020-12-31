<?php

namespace HomeConstruct\BuildBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use HomeConstruct\BuildBundle\Entity\TypeArbre;

class TypeArbreFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $types= [
            [
                'nom' => 'Feuillus',
                'prix'=> '7'
            ],
            [
                'nom' => 'Résineux',
                'prix' => '15'
            ]
        ];

        $this->persistStatus($manager, $types);
        $manager->flush();
    }

    public function persistStatus($manager, $types)
    {
        foreach ($types as $type) {
            $typeArbre = new TypeArbre();
            $typeArbre->setNom($type['nom']);
            $typeArbre->setPrix($type['prix']);
            $manager->persist($typeArbre);
        }
    }
}
