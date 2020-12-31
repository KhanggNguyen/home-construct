<?php

namespace HomeConstruct\BuildBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use HomeConstruct\BuildBundle\Entity\TypeIsolationPlancher;

class TypeIsolationPlancherFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $types= [
            [
                'nom' => 'Dalle flottante',
                'prix' => 50
            ],
            [
                'nom' => 'Fond de coffrage',
                'prix' => 50
            ],
            [
                'nom' => 'Lino',
                'prix' => 50
            ],
            [
                'nom' => 'Sol PVC',
                'prix' => 50
            ]
        ];

        $this->persistStatus($manager, $types);
        $manager->flush();
    }

    public function persistStatus($manager, $types)
    {
        foreach ($types as $type) {
            $typeIsolationPlancher = new TypeIsolationPlancher();
            $typeIsolationPlancher->setNom($type['nom']);
            $typeIsolationPlancher->setPrix($type['prix']);
            $manager->persist($typeIsolationPlancher);
        }
    }
}
