<?php

namespace HomeConstruct\BuildBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use HomeConstruct\BuildBundle\Entity\TypeCouverture;

class TypeCouvertureFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $types= [
            [
                'nom' => 'Acier',
                'prix' => '25'
            ],
            [
                'nom' => 'Ardoise',
                'prix' => '65'
            ],
            [
                'nom' => 'Chaume',
                'prix' => '135'
            ],
            [
                'nom' => 'Shingle',
                'prix' => '13'
            ],
            [
                'nom' => 'Tuile',
                'prix' => '65'
            ],
            [
                'nom' => 'Zinc',
                'prix' => '100'
            ]
        ];

        $this->persistStatus($manager, $types);
        $manager->flush();
    }

    public function persistStatus($manager, $types)
    {
        foreach ($types as $type) {
            $typeCouverture = new TypeCouverture();
            $typeCouverture->setNom($type['nom']);
            $typeCouverture->setPrix($type['prix']);
            $manager->persist($typeCouverture);
        }
    }
}
