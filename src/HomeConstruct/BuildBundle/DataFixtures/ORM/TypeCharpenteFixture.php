<?php

namespace HomeConstruct\BuildBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use HomeConstruct\BuildBundle\Entity\TypeCharpente;

class TypeCharpenteFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $types= [
            [
                'nom' => 'Fermette bois',
                'prix' => '30'
            ],
            [
                'nom' => 'Traditionnelle bois',
                'prix' => '65'
            ],
            [
                'nom' => 'Métallique',
                'prix' => '80'
            ],
            [
                'nom' => 'Béton armé',
                'prix' => '90'
            ],
            [
                'nom' => 'Bois lamellé collé',
                'prix' => '80'
            ],

        ];
        $this->persistStatus($manager, $types);
        $manager->flush();
    }

    public function persistStatus($manager, $types)
    {
        foreach ($types as $type) {
            $typeCharpente = new TypeCharpente();
            $typeCharpente->setNom($type['nom']);
            $typeCharpente->setPrix($type['prix']);
            $manager->persist($typeCharpente);
        }
    }
}
