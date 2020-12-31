<?php

namespace HomeConstruct\BuildBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use HomeConstruct\BuildBundle\Entity\TypePlomberie;

class TypePlomberieFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $types= [
            [
                'nom' => 'Creation salle de bain (totalité)',
                'prix' => 3500,
                'mainOeuvre' => 2700,
                'parM2' => false
            ],
            [
                'nom' => 'Salle de bain italienne',
                'prix' => 4000,
                'mainOeuvre' => 3500,
                'parM2' => false
            ],
            [
                'nom' => 'Douche cabine',
                'prix' => 500,
                'mainOeuvre' => 500,
                'parM2' => false
            ],
            [
                'nom' => 'Baignoire',
                'prix' => 750,
                'mainOeuvre' => 350,
                'parM2' => false
            ],
            [
                'nom' => 'Parquet salle de bain',
                'prix' => 50,
                'mainOeuvre' => 75,
                'parM2' => true
            ],
            [
                'nom' => 'Peinture salle de bain',
                'prix' => 6,
                'mainOeuvre' => 35,
                'parM2' => true
            ]
        ];

        $this->persistStatus($manager, $types);
        $manager->flush();
    }

    public function persistStatus($manager, $types)
    {
        foreach ($types as $type) {
            $typePlomberie = new TypePlomberie();
            $typePlomberie->setNom($type['nom']);
            $typePlomberie->setPrix($type['prix']);
            $typePlomberie->setMainOeuvre($type['mainOeuvre']);
            $typePlomberie->setParM2($type['parM2']);
            $manager->persist($typePlomberie);
        }
    }
}
