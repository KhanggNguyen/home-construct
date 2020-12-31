<?php

namespace HomeConstruct\BuildBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use HomeConstruct\BuildBundle\Entity\TypeEscalier;

class TypeEscalierFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $types= [
            [
                'nom' => 'Escalier Droit'
            ],
            [
                'nom' => 'Escalier Tournant'
            ],
            [
                'nom' => 'Escalier Hélicoïdal'
            ],
        ];

        $this->persistStatus($manager, $types);
        $manager->flush();
    }

    public function persistStatus($manager, $types)
    {
        foreach ($types as $type) {
            $typeEscalier = new TypeEscalier();
            $typeEscalier->setNom($type['nom']);
            $manager->persist($typeEscalier);
        }
    }
}
