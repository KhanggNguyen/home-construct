<?php
/**
 * Created by PhpStorm.
 * User: kweidner
 * Date: 26/02/2019
 * Time: 12:01
 */

namespace HomeConstruct\BuildBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use HomeConstruct\BuildBundle\Entity\SecondOeuvre;

class SecondOeuvreFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        /*$secondOeuvre = new SecondOeuvre();
        $secondOeuvre->setIsolationExterieure();
        $secondOeuvre->setEnduitExterieur();
        $secondOeuvre->addMenuiseriesExterieure();
        $manager->persist($secondOeuvre);
        $manager->flush();*/
    }
}
