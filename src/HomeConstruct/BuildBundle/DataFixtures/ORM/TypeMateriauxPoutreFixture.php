<?php

namespace HomeConstruct\BuildBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use HomeConstruct\BuildBundle\Entity\TypeMateriauxPoutre;

class TypeMateriauxPoutreFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $types= [
            [
                'nom' => 'Acier',
                'prix' => 75
            ],
            [
                'nom' => 'Aluminium',
                'prix' => 250
            ],
            [
                'nom' => 'Bois',
                'prix' => 30
            ],
            [
                'nom' => 'Fer',
                'prix' => 60
            ]
        ];



        $this->persistStatus($manager, $types);
        $manager->flush();
    }

    public function persistStatus($manager, $types)
    {
        foreach ($types as $type) {
            $typeMateriauxPoutre = new TypeMateriauxPoutre();
            $typeMateriauxPoutre->setNom($type['nom']);
            $typeMateriauxPoutre->setPrix($type['prix']);
            $manager->persist($typeMateriauxPoutre);
        }
    }
}