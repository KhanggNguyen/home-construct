<?php

namespace HomeConstruct\BuildBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use HomeConstruct\BuildBundle\Entity\TarifArbre;

class TarifArbreFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $types= [
            [
                'nombreArbre' => '1',
                'prix'=> '132'
            ],
            [
                'nombreArbre' => '2',
                'prix' => '200'
            ],
            [
                'nombreArbre' => '3',
                'prix' => '263'
            ],
            [
                'nombreArbre' => '4',
                'prix' => '330'
            ],
            [
                'nombreArbre' => '5',
                'prix' => '390'
            ]
        ];

        $this->persistStatus($manager, $types);
        $manager->flush();
    }

    public function persistStatus($manager, $types)
    {
        foreach ($types as $type) {
            $tarif = new TarifArbre();
            $tarif->setNombreArbre($type['nombreArbre']);
            $tarif->setPrix($type['prix']);
            $manager->persist($tarif);
        }
    }
}
