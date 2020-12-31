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
use HomeConstruct\BuildBundle\Entity\GrosOeuvre;

class GrosOeuvreFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        /*$grosOeuvre = new GrosOeuvre($this->getDoctrine()->getManager());
        $grosOeuvre->setInformationBase();
        $grosOeuvre->addPiece();
        $grosOeuvre->addMenuiseriesExterieure();

        $manager->persist($grosOeuvre);
        $manager->flush();*/
    }
}
