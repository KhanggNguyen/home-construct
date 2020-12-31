<?php

namespace HomeConstruct\BuildBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use HomeConstruct\BuildBundle\Entity\Travaux;

class TravauxFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $types= [
            [
                'nom' => 'Nivellement',
                'prix'=> '10'
            ],
            [
                'nom' => 'Tranchée',
                'prix' => '10'
            ],
            [
                'nom' => 'Fouilles',
                'prix' => '30'
            ],
            [
                'nom' => 'Déblais',
                'prix' => '25'
            ],
            [
                'nom' => 'Remblais Terre',
                'prix' => '20'
            ],
            [
                'nom' => 'Remblais Sable',
                'prix' => '45'
            ]
        ];

        $this->persistStatus($manager, $types);
        $manager->flush();
    }

    public function persistStatus($manager, $types)
    {
        foreach ($types as $type) {
            $travaux = new Travaux();
            $travaux->setNom($type['nom']);
            $travaux->setPrix($type['prix']);
            $manager->persist($travaux);
        }
    }
}
