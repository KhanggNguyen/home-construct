<?php

namespace HomeConstruct\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/user")
     */
    public function indexAction()
    {
        return $this->render('HomeConstructUserBundle:Default:index.html.twig');
    }
}
