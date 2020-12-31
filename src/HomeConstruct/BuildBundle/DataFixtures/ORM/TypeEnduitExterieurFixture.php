<?php

namespace HomeConstruct\BuildBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use HomeConstruct\BuildBundle\Entity\TypeEnduitExterieur;

class TypeEnduitExterieurFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $types= [
            [
                'nom' => 'Monocouche',
                'prix'=> '25'
            ],
            [
                'nom' => 'Crépi',
                'prix' => '35'
            ],
            [
                'nom' => 'Chaux',
                'prix' => '60'
            ],
            [
                'nom' => 'Climent',
                'prix' => '30'
            ],
            [
                'nom' => 'Parement organique',
                'prix' => '35'
            ],
            [
                'nom' => 'Siloxane',
                'prix' => '25'
            ],
            [
                'nom' => 'Silicate',
                'prix' => '32'
            ],
        ];

        $this->persistStatus($manager, $types);
        $manager->flush();
    }

    public function persistStatus($manager, $types)
    {
        foreach ($types as $type) {
            $typeEnduitExterieur = new TypeEnduitExterieur();
            $typeEnduitExterieur->setNom($type['nom']);
            $typeEnduitExterieur->setPrix($type['prix']);
            $manager->persist($typeEnduitExterieur);
        }
    }
}
