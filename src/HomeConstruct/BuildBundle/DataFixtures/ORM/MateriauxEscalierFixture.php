<?php

namespace HomeConstruct\BuildBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use HomeConstruct\BuildBundle\Entity\MateriauxEscalier;

class MateriauxEscalierFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $types= [
            [
                'nom' => 'Acier',
                'prix' => 1000
            ],
            [
                'nom' => 'Bois',
                'prix' => 2000
            ],
            [
                'nom' => 'Alu',
                'prix' => 5000
            ],
            [
                'nom' => 'Pierre',
                'prix' => 1500
            ],
            [
                'nom' => 'Béton',
                'prix' => 500
            ],
            [
                'nom' => 'Verre',
                'prix' => 2500
            ],
            [
                'nom' => 'Inox',
                'prix' => 3000
            ]
        ];

        $this->persistStatus($manager, $types);
        $manager->flush();
    }

    public function persistStatus($manager, $types)
    {
        foreach ($types as $type) {
            $materiauxEscalier = new MateriauxEscalier();
            $materiauxEscalier->setNom($type['nom']);
            $materiauxEscalier->setPrix($type['prix']);
            $manager->persist($materiauxEscalier);
        }
    }
}
