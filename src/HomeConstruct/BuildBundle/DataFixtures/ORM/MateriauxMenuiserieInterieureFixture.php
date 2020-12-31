<?php

namespace HomeConstruct\BuildBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use HomeConstruct\BuildBundle\Entity\MateriauxMenuiserieInterieure;

class MateriauxMenuiserieInterieureFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $types= [
            [
                'nom' => 'Verre',
            ],
            [
                'nom' => 'Bois'
            ],
            [
                'nom' => 'Alu'
            ],
            [
                'nom' => 'PVC'
            ],
            [
                'nom' => 'Pin'
            ],
            [
                'nom' => 'Sapin'
            ],
            [
                'nom' => 'Melzene'
            ],
            [
                'nom' => 'Chatignier'
            ],
            [
                'nom' => 'Bambou'
            ],

        ];

        $this->persistStatus($manager, $types);
        $manager->flush();
    }

    public function persistStatus($manager, $types)
    {
        foreach ($types as $type) {
            $materiauxMenuiserieInterieure = new MateriauxMenuiserieInterieure();
            $materiauxMenuiserieInterieure->setNom($type['nom']);

            $manager->persist($materiauxMenuiserieInterieure);
        }
    }
}
