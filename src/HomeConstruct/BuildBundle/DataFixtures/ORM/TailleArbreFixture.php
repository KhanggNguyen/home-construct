<?php

namespace HomeConstruct\BuildBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use HomeConstruct\BuildBundle\Entity\TailleArbre;

class TailleArbreFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $types= [
            [
                'nom' => '2 - 5',
                'tarifAbattageNettoyageMin' => '100',
                'tarifAbattageNettoyageMax' => '250',
                'tarifDessouchageMin' => '100',
                'tarifDessouchageMax' => '200',
                'taille' => 5,
                'prix' => 450
            ],
            [
                'nom' => '5 - 10',
                'tarifAbattageNettoyageMin' => '150',
                'tarifAbattageNettoyageMax' => '250',
                'tarifDessouchageMin' => '100',
                'tarifDessouchageMax' => '300',
                'taille' => 10,
                'prix' => 550
            ],
            [
                'nom' => '10 - 15',
                'tarifAbattageNettoyageMin' => '350',
                'tarifAbattageNettoyageMax' => '500',
                'tarifDessouchageMin' => '300',
                'tarifDessouchageMax' => '400',
                'taille' => 15,
                'prix' => 900
            ],
            [
                'nom' => '15 - 20',
                'tarifAbattageNettoyageMin' => '500',
                'tarifAbattageNettoyageMax' => '600',
                'tarifDessouchageMin' => '400',
                'tarifDessouchageMax' => '500',
                'taille' => 20,
                'prix' => 1100
            ],
            [
                'nom' => '20+',
                'tarifAbattageNettoyageMin' => '600',
                'tarifAbattageNettoyageMax' => '700',
                'tarifDessouchageMin' => '500',
                'tarifDessouchageMax' => '600',
                'taille' => 25,
                'prix' => 1300
            ],
        ];

        $this->persistStatus($manager, $types);
        $manager->flush();
    }

    public function persistStatus($manager, $types)
    {
        foreach ($types as $type) {
            $tailleArbre = new TailleArbre();
            $tailleArbre->setNom($type['nom']);
            $tailleArbre->setTarifAbattageNettoyageMin($type['tarifAbattageNettoyageMin']);
            $tailleArbre->setTarifAbattageNettoyageMax($type['tarifAbattageNettoyageMax']);
            $tailleArbre->setTarifDessouchageMin($type['tarifDessouchageMin']);
            $tailleArbre->setTarifDessouchageMax($type['tarifDessouchageMax']);
            $tailleArbre->setPrix($type['prix']);
            $tailleArbre->setTaille($type['taille']);
            $manager->persist($tailleArbre);
        }
    }
}
