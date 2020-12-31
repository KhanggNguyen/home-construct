<?php

namespace HomeConstruct\BuildBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use HomeConstruct\BuildBundle\Entity\ReseauGazNaturel;

class TypeReseauGazNaturelFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $types= [
            [
                'nom' => 'Butagaz',
                'prixForfait'=>185.0
            ],
            [
                'nom' => 'Direct energie',
                'prixForfait'=>160.0
            ],
            [
                'nom' => 'Dyneff gaz',
                'prixForfait'=>155.0
            ],
            [
                'nom' => 'EDF',
                'prixForfait'=>175.0
            ],
            [
                'nom' => 'ekWateur',
                'prixForfait'=>170.0
            ],
            [
                'nom' => 'ENGIE',
                'prixForfait'=>165.0
            ]

        ];

        $this->persistStatus($manager, $types);
        $manager->flush();
    }

    public function persistStatus($manager, $types)
    {
        foreach ($types as $type) {
            $typeReseau = new ReseauGazNaturel();
            $typeReseau->setNom($type['nom']);
            $typeReseau->setPrixForfait($type['prixForfait']);
            $manager->persist($typeReseau);
        }
    }
}
