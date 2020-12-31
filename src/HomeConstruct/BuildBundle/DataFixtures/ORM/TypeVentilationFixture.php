<?php

namespace HomeConstruct\BuildBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use HomeConstruct\BuildBundle\Entity\TypeVentilation;

class TypeVentilationFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $types= [
            [
                'nom' => 'Naturelle',
                'prix'=> '1500'
            ],
            [
                'nom' => 'VMC simple flux autoréglable',
                'prix' => '1200'
            ],
            [
                'nom' => 'VMC simple flux hygroréglable',
                'prix' => '750'
            ],
            [
                'nom' => 'VMC double flux',
                'prix' => '2500'
            ],
        ];

        $this->persistStatus($manager, $types);
        $manager->flush();
    }

    public function persistStatus($manager, $types)
    {
        foreach ($types as $type) {
            $typeVentilation = new TypeVentilation();
            $typeVentilation->setNom($type['nom']);
            $typeVentilation->setPrix($type['prix']);
            $manager->persist($typeVentilation);
        }
    }
}
