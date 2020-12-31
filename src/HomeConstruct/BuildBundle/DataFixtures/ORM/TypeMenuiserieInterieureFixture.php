<?php

namespace HomeConstruct\BuildBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use HomeConstruct\BuildBundle\Entity\TypeMenuiserieInterieure;

class TypeMenuiserieInterieureFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $types= [
            [
                'nom' => 'Porte'
            ],
            [
                'nom' => 'Escalier droit'
            ],
            [
                'nom' => 'Escalier tournant'
            ],
            [
                'nom' => 'Escalier hélicoîdal'
            ],
            [
                'nom' => 'Lambris'
            ],
            [
                'nom' => 'Ventilation'
            ],
            [
                'nom' => 'Climatisation'
            ]
        ];

        $this->persistStatus($manager, $types);
        $manager->flush();
    }

    public function persistStatus($manager, $types)
    {
        foreach ($types as $type) {
            $typeMenuiserieInterieure = new TypeMenuiserieInterieure();
            $typeMenuiserieInterieure->setNom($type['nom']);
            $manager->persist($typeMenuiserieInterieure);
        }
    }
}
