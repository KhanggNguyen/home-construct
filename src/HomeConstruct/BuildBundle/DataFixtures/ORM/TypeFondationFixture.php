<?php

namespace HomeConstruct\BuildBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use HomeConstruct\BuildBundle\Entity\Fondation;
use HomeConstruct\BuildBundle\Entity\TypeFondation;

class TypeFondationFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $types= [
            [
                'nom' => 'Semelle filante'
            ],
            [
                'nom' => 'Semelle ponctuelle'
            ],
            [
                'nom' => 'Radier'
            ],
        ];

        $this->persistStatus($manager, $types);
        $manager->flush();
    }

    public function persistStatus($manager, $types)
    {
        foreach ($types as $type) {
            $ferraillage = new TypeFondation();
            $ferraillage->setNom($type['nom']);
            $manager->persist($ferraillage);
        }
    }
}
