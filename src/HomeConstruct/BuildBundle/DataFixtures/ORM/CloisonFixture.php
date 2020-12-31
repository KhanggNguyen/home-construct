<?php

namespace HomeConstruct\BuildBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use HomeConstruct\BuildBundle\Entity\Cloison;

class CloisonFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $types= [
            [
                'nom' => 'Brique'
            ],
            [
                'nom' => 'Amovible'
            ],
            [
                'nom' => 'Sèche'
            ],
            [
                'nom' => 'Verre'
            ],
            [
                'nom' => 'Pour pièce humide'
            ],
            [
                'nom' => 'Japonaise'
            ],
            [
                'nom' => 'Végétale'
            ]
        ];

        $this->persistStatus($manager, $types);
        $manager->flush();
    }

    public function persistStatus($manager, $types)
    {
        foreach ($types as $type) {
            $cloison = new Cloison();
            $cloison->setNom($type['nom']);
            $manager->persist($cloison);
        }
    }
}
