<?php

namespace HomeConstruct\BuildBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use HomeConstruct\BuildBundle\Entity\TypePlancher;

class TypePlancherFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $types= [
            [
                'nom' => 'Toit plat',
                'prix' => 90
            ],
            [
                'nom' => 'Etage',
                'prix' => 150
            ],
            [
                'nom' => 'Vide sanitaire',
                'prix' => 100
            ],
            [
                'nom' => 'Bois',
                'prix' => 50
            ]
        ];

        $this->persistStatus($manager, $types);
        $manager->flush();
    }

    public function persistStatus($manager, $types)
    {
        foreach ($types as $type) {
            $typePlancher = new TypePlancher();
            $typePlancher->setNom($type['nom']);
            $typePlancher->setPrix($type['prix']);
            $manager->persist($typePlancher);
        }
    }
}
