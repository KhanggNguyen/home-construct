<?php

namespace HomeConstruct\BuildBundle\Controller\Administration;

use HomeConstruct\BuildBundle\Entity\Assainissement;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Administration controller.
 *
 * @Route("/administration")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class AdministrationController extends Controller{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/profile/", name="home_construct_administration_profile")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function profileAction(Request $request)
    {
        $page = array(
            'title' => 'Administration'
        );
        $titreOnglet = "Administration";

        $object = "Gestion de l'administration";
        return $this->render('@HomeConstructBuild/Administration/profile.html.twig', array(
            'page' => $page,
            'object' => $object,
            'titreOnglet' => $titreOnglet
        ));
    }

    /**
     * @Route("/gros-oeuvre/profile", name="home_construct_administration_grosOeuvre_profile")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function profileGrosOeuvreAction(Request $request)
    {
        $page = array(
            'title' => 'Administration',
            'sub_title'=>'Administration du Gros Oeuvre'
        );
        $titreOnglet = "Administration Gros Oeuvre";

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/profile.html.twig', array(
            'page' => $page,
            'titreOnglet' => $titreOnglet
        ));
    }

    /**
     * @Route("/second-oeuvre/profile", name="home_construct_administration_secondOeuvre_profile")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function profileSecondOeuvreAction(Request $request)
    {
        $page = array(
            'title' => 'Administration',
            'sub_title'=>'Administration du Second Oeuvre'
        );
        $titreOnglet = "Administration Second Oeuvre";

        return $this->render('@HomeConstructBuild/Administration/SecondOeuvre/profile.html.twig', array(
            'page' => $page,
            'titreOnglet' => $titreOnglet
        ));
    }

    /**
     * @Route("/test", name="home_construct_administration_test")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function testAction(Request $request)
    {
        $page = array(
            'title' => 'Administration'
        );
        $titreOnglet = "Administration";

        $object = "Gestion de l'administration";
        return $this->render('@HomeConstructBuild/Administration/test.html.twig', array(
            'page' => $page,
            'object' => $object,
            'titreOnglet' => $titreOnglet
        ));
    }

}


