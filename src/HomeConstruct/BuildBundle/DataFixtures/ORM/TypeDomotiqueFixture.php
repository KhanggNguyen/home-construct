<?php

namespace HomeConstruct\BuildBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use HomeConstruct\BuildBundle\Entity\TypeDomotique;

class TypeDomotiqueFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $types= [
            [
                'nom' => 'Alarme',
                'prix'=> 700
            ],
            [
                'nom' => 'Box de contrôle',
                'prix'=> 1000
            ],
            [
                'nom' => 'Câblage',
                'prix'=> 2000
            ],
            [
                'nom' => 'Caméra de surveillance',
                'prix'=> 300
            ],
            [
                'nom' => 'Capteur',
                'prix'=> 30
            ],
            [
                'nom' => 'Ecran',
                'prix'=> 250
            ],
            [
                'nom' => 'Élément de commande',
                'prix'=> 350
            ],
            [
                'nom' => 'Interrupteur',
                'prix'=> 100
            ],
            [
                'nom' => 'Motorisation du portail',
                'prix'=> 900
            ],
            [
                'nom' => 'Volets',
                'prix'=> 350
            ]
        ];

        $this->persistStatus($manager, $types);
        $manager->flush();
    }

    public function persistStatus($manager, $types)
    {
        foreach ($types as $type) {
            $typeDomotique = new TypeDomotique();
            $typeDomotique->setNom($type['nom']);
            $typeDomotique->setPrix($type['prix']);
            $manager->persist($typeDomotique);
        }
    }
}
