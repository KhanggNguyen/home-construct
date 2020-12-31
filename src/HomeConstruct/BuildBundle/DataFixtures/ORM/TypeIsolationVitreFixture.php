<?php

namespace HomeConstruct\BuildBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use HomeConstruct\BuildBundle\Entity\TypeIsolationVitre;

class TypeIsolationVitreFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $types= [
            [
                'nom' => 'Double vitrage en verre',
                'prix' => 100
            ]
        ];

        $this->persistStatus($manager, $types);
        $manager->flush();
    }

    public function persistStatus($manager, $types)
    {
        foreach ($types as $type) {
            $typeIsolationVitre = new TypeIsolationVitre();
            $typeIsolationVitre->setNom($type['nom']);
            $typeIsolationVitre->setPrix($type['prix']);
            $manager->persist($typeIsolationVitre);
        }
    }
}
