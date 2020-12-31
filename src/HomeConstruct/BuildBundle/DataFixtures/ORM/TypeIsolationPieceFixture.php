<?php

namespace HomeConstruct\BuildBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use HomeConstruct\BuildBundle\Entity\TypeIsolationPiece;

class TypeIsolationPieceFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $types= [
            [
                'nom' => 'Laine de verre'
            ],
            [
                'nom' => 'Laine de roche'
            ],
            [
                'nom' => 'Laine de chanvre'
            ],
            [
                'nom' => 'Polystyrène'
            ],
            [
                'nom' => 'Plume de canard'
            ],
            [
                'nom' => 'Laine de bois'
            ],
            [
                'nom' => 'Laine de lin'
            ],
            [
                'nom' => 'Laine de coton'
            ],
            [
                'nom' => 'Textile recyclé'
            ]
        ];

        $this->persistStatus($manager, $types);
        $manager->flush();
    }

    public function persistStatus($manager, $types)
    {
        foreach ($types as $type) {
            $isolationPiece = new TypeIsolationPiece();
            $isolationPiece->setNom($type['nom']);
            $manager->persist($isolationPiece);
        }
    }
}
