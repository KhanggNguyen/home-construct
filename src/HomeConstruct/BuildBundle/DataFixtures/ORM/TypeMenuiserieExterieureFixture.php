<?php

namespace HomeConstruct\BuildBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use HomeConstruct\BuildBundle\Entity\TypeMenuiserieExterieure;

class TypeMenuiserieExterieureFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $types= [
            [
                'nom' => 'Fenêtre'
            ],
            [
                'nom' => 'Porte'
            ],
            [
                'nom' => 'Véranda'
            ],
            [
                'nom' => 'Volet'
            ]
        ];

        $this->persistStatus($manager, $types);
        $manager->flush();
    }

    public function persistStatus($manager, $types)
    {
        foreach ($types as $type) {
            $typeMenuiserieExterieure = new TypeMenuiserieExterieure();
            $typeMenuiserieExterieure->setNom($type['nom']);

            $manager->persist($typeMenuiserieExterieure);
        }
    }
}
