<?php

namespace HomeConstruct\BuildBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use HomeConstruct\BuildBundle\Entity\MateriauxMenuiserieExterieure;

class MateriauxMenuiserieExterieureFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $types= [
            [
                'nom' => 'Acier'
            ],
            [
                'nom' => 'Bois'
            ],
            [
                'nom' => 'Alu'
            ],
            [
                'nom' => 'Composite'
            ],
            [
                'nom' => 'PVC'
            ],
            [
                'nom' => 'Verre'
            ]
        ];

        $this->persistStatus($manager, $types);
        $manager->flush();
    }

    public function persistStatus($manager, $types)
    {
        foreach ($types as $type) {
            $materiauxMenuiserieExterieure = new MateriauxMenuiserieExterieure();
            $materiauxMenuiserieExterieure->setNom($type['nom']);
            $manager->persist($materiauxMenuiserieExterieure);
        }
    }
}
