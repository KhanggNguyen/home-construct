<?php

namespace HomeConstruct\BuildBundle\Controller;

use HomeConstruct\FoBundle\Form\ContactType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Default controller.
 *
 * @Route("/inside")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/construct", name="home_construct_accueil")
     */
    public function indexAction()
    {
        return $this->redirectToRoute('home_construct_projet_list');
    }

    /**
     * @Route("/contact", name="home_construct_contact")
     */
    public function contactAction(Request $request)
    {
        $form = $this->createForm(ContactType::class);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            try {
                $this->get('symracine_mail.mailer')
                    ->sendMail('HomeConstructFoBundle:Mail:contact',
                    $request->request->get('contact'),
                        $this->getUser()->getEmail()
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

            return $this->redirectToRoute('home_construct_contact');
        }

        return $this->render('@HomeConstructBuild/Default/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
