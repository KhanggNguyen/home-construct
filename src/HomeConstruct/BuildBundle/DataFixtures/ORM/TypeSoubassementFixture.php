<?php

namespace HomeConstruct\BuildBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use HomeConstruct\BuildBundle\Entity\TypeSoubassement;

class TypeSoubassementFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $types= [
            [
                'nom' => 'Hérisson',
                'prixForfait'=>15.0
            ],
            [
                'nom' => 'Vide sanitaire',
                'prixForfait'=>40.0

            ],
            [
                'nom' => 'Sous-sol',
                'prixForfait'=>200.0

            ]
        ];

        $this->persistStatus($manager, $types);
        $manager->flush();
    }

    public function persistStatus($manager, $types)
    {
        foreach ($types as $type) {
            $typeSoubassement = new TypeSoubassement();
            $typeSoubassement->setNom($type['nom']);
            $typeSoubassement->setPrixForfait($type['prixForfait']);
            $manager->persist($typeSoubassement);
        }
    }
}
