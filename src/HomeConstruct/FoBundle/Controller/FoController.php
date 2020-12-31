<?php

namespace HomeConstruct\FoBundle\Controller;

use HomeConstruct\FoBundle\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class FoController extends Controller
{
    /**
     * @Route("/", name="home_construct_accueil")
     */
    public function indexAction()
    {
        return $this->render('HomeConstructFoBundle::accueil.html.twig');
    }

    /**
     * @Route("/contact", name="home_construct_fo_contact")
     */
    public function contactAction(Request $request)
    {
        $form = $this->get('form.factory')->create(ContactType::class);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {


            try {
                $this->get('symracine_mail.mailer')->sendMail(
                    'HomeConstructFoBundle:Mail:contact',
                    $request->request->get('contact'),
                    $this->getParameter('mailer_user')
                );
            } catch (\Exception $e) {
                $request->getSession()->getFlashBag()->add(
                    'danger',
                    $this->get('translator')->trans('contact.error', [], 'HomeConstructFoBundle')
                );
            }

            $request->getSession()->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('contact.confirm', [], 'HomeConstructFoBundle')
            );

            return $this->redirectToRoute('home_construct_fo_contact');
        }

        return $this->render('HomeConstructFoBundle::contact.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/mentions-legales", name="home_construct_mentions_legales")
     */
    public function legalNoticeAction()
    {
        return $this->render('HomeConstructFoBundle::mentions-legales.html.twig');
    }


    /**
     * @Route("/protection-des-donnees", name="home_construct_dataprotection")
     */
    public function dataProtectionAction()
    {
        return $this->render('HomeConstructFoBundle::dataProtection.html.twig');
    }
}
