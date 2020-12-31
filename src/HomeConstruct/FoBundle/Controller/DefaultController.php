<?php

namespace HomeConstruct\FoBundle\Controller;
 
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use HomeConstruct\FoBundle\Controller\FoController as BaseController;

/**
 * Front office controller.
 *
 * @Route("/")
 */
class DefaultController extends BaseController
{

    /**
     * @Route("/", name="home_construct_fo_accueil")
     */
    public function accueilAction(Request $request)
    {
        if($this->isGranted('ROLE_USER')){
            return $this->redirectToRoute('home_construct_projet_list');
        }else{
            return $this->redirectToRoute('fos_user_security_login');
        }
    }



}
