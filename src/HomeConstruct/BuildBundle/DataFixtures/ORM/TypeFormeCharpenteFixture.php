<?php

namespace HomeConstruct\BuildBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use HomeConstruct\BuildBundle\Entity\FormeCharpente;
use Symfony\Component\Form\Form;

class TypeFormeCharpenteFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $types= [
            [
                'nom' => '2 pentes'
            ],
            [
                'nom' => '3 pentes'
            ],
            [
                'nom' => '4 pentes'
            ],
            [
                'nom' => 'L'
            ]
        ];
        $this->persistStatus($manager, $types);
        $manager->flush();
    }

    public function persistStatus($manager, $types)
    {
        foreach ($types as $type) {
            $formeCharpente = new FormeCharpente();
            $formeCharpente->setNom($type['nom']);
            $manager->persist($formeCharpente);
        }
    }
}
