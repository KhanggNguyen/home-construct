<?php

namespace HomeConstruct\BuildBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use HomeConstruct\BuildBundle\Entity\TypeMateriauxMur;

class TypeMateriauxMurFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $types= [
            [
                'nom' => 'Parpaing',
                'prix' => 40
            ],
            [
                'nom' => 'Beton Cellulaire',
                'prix' => 80
            ],
            [
                'nom' => 'pierre',
                'prix' => 50
            ],
            [
                'nom' => 'brique',
                'prix' => 30
            ]
        ];

        $this->persistStatus($manager, $types);
        $manager->flush();
    }

    public function persistStatus($manager, $types)
    {
        foreach ($types as $type) {
            $typeMateriauxMur = new TypeMateriauxMur();
            $typeMateriauxMur->setNom($type['nom']);
            $typeMateriauxMur->setPrix($type['prix']);
            $manager->persist($typeMateriauxMur);
        }
    }
}
