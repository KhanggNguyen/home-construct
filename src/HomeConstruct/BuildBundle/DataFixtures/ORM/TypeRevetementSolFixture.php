<?php

namespace HomeConstruct\BuildBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use HomeConstruct\BuildBundle\Entity\TypeRevetementSol;

class TypeRevetementSolFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $types= [
            [
                'nom' => 'Béton naturel',
                'prix' => 150
            ],
            [
                'nom' => 'Carreaux de ciment',
                'prix' => 50
            ],
            [
                'nom' => 'Cuir',
                'prix' => 225
            ],
            [
                'nom' => 'Grès cérame',
                'prix' => 60
            ],
            [
                'nom' => 'Jonc de mer',
                'prix' => 25
            ],
            [
                'nom' => 'Liège',
                'prix' => 20
            ],
            [
                'nom' => 'Lino',
                'prix' => 40
            ],
            [
                'nom' => 'Moquette',
                'prix' => 50
            ],
            [
                'nom' => 'Parquet massif',
                'prix' => 100
            ],
            [
                'nom' => 'Parquet flottant',
                'prix' => 80
            ],
            [
                'nom' => 'Parquet stratifié',
                'prix' => 30
            ],
            [
                'nom' => 'PVC',
                'prix' => 30
            ],
            [
                'nom' => 'Sisal',
                'prix' => 20
            ],
            [
                'nom' => 'Terre cuite',
                'prix' => 50
            ],
            [
                'nom' => 'Tomettes',
                'prix' => 60
            ]
        ];

        $this->persistStatus($manager, $types);
        $manager->flush();
    }

    public function persistStatus($manager, $types)
    {
        foreach ($types as $type) {
            $typeRevetementSol = new TypeRevetementSol();
            $typeRevetementSol->setNom($type['nom']);
            $typeRevetementSol->setPrix($type['prix']);
            $manager->persist($typeRevetementSol);
        }
    }
}
