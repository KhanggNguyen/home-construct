<?php

namespace HomeConstruct\BuildBundle\Controller\Administration\GrosOeuvre;

use HomeConstruct\BuildBundle\Entity\EvacuationTerrassement;
use HomeConstruct\BuildBundle\Form\EvacuationTerrassementType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * TypeEvacuationTerrassement controller.
 *
 * @Route("/administration/type-evacuation-terrassement")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class TypeEvacuationTerrassementController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/", name="home_construct_evacuation_terrassement_creation")
     * @Route("/edit/{idEvacuationTerrassement}", name="home_construct_evacuation_terrassement_edit")
     * @ParamConverter("evacuationTerrassement", options={"mapping" : {"idEvacuationTerrassement" : "id"}})
     */
    public function formAction(Request $request, EvacuationTerrassement $evacuationTerrassement=null){
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        if(!$evacuationTerrassement){
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Création d\'un type d\'évacuation terrassement'
            );
            $titreOnglet = "Ajout type evacuation";
            $evacuationTerrassement = new EvacuationTerrassement();
        }else{
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Modification d\'un type d\'évacuation terrassement'
            );
            $titreOnglet = "Modif type évacuation";
        }
        $entities= $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:EvacuationTerrassement')
            ->findAll();
        $form = $this->createForm(EvacuationTerrassementType::class, $evacuationTerrassement);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!$evacuationTerrassement->getId()){
                $notif='Type d\'évacuation terrassement ajouté';
            }else{
                $notif='Type d\'évacuation terrassement modifié';
            }
            $em->persist($evacuationTerrassement);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_evacuation_terrassement_list');
        }

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TypeEvacuationTerrassement/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_evacuation_terrassement_deleteone")
     */
    public function deleteOneAction(Request $request, EvacuationTerrassement $evacuationTerrassement)
    {
        $notif = "Type ".$evacuationTerrassement->getNom()." supprimé(e)";
        $em = $this->getDoctrine()->getManager();
        $em->remove($evacuationTerrassement);
        $em->flush();
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_evacuation_terrassement_list');
    }

    /**
     * @Route("/liste/", name="home_construct_evacuation_terrassement_list")
     */
    public function listAction(Request $request)
    {
        $page = array(
            'title' => 'Administration',
            'sub_title' => 'Types d\'évacuations terrassement'
        );

        $titreOnglet = "Type Evacuation";

        $object = 'TypeEvacuationTerrassement';

        $entities = $this
            ->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:EvacuationTerrassement')
            ->findAll();

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TypeEvacuationTerrassement/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'object' => $object,
            'titreOnglet' => $titreOnglet
        ));
    }

}