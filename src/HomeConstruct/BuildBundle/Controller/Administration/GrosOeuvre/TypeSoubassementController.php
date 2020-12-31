<?php

namespace HomeConstruct\BuildBundle\Controller\Administration\GrosOeuvre;

use HomeConstruct\BuildBundle\Entity\TypeSoubassement;
use HomeConstruct\BuildBundle\Form\TypeSoubassementType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Type Soubassement controller.
 *
 * @Route("/administration/type-soubassement")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class TypeSoubassementController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/", name="home_construct_type_soubassement_creation")
     * @Route("/edit/{idTypeSoubassement}", name="home_construct_type_soubassement_edit")
     * @ParamConverter("typeSoubassement", options={"mapping" : {"idTypeSoubassement" : "id"}})
     */
    public function formAction(Request $request, TypeSoubassement $typeSoubassement=null){
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        if(!$typeSoubassement){
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Création d\'un type de soubassement'
            );
            $titreOnglet = "Ajout Type Soubassement";
            $typeSoubassement = new TypeSoubassement();
        }else{
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Modification d\'un type de soubassement'
            );
            $titreOnglet = "Modif Type Soubassement";
        }
        $entities= $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeSoubassement')
            ->findAll();
        $form = $this->createForm(TypeSoubassementType::class, $typeSoubassement);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!$typeSoubassement->getId()){
                $notif='Type'. $typeSoubassement->getNom() .'ajouté';
            }else{
                $notif='Type de soubassement modifié';
            }
            $em->persist($typeSoubassement);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_type_soubassement_list');
        }

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TypeSoubassement/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_type_soubassement_deleteone")
     */
    public function deleteOneAction(Request $request, TypeSoubassement $typeSoubassement)
    {
        $notif = $typeSoubassement->getNom()." supprimé(e)";
        $em = $this->getDoctrine()->getManager();
        $em->remove($typeSoubassement);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_type_soubassement_list');
    }

    /**
     * @Route("/liste/", name="home_construct_type_soubassement_list")
     */
    public function listAction(Request $request)
    {
        $page = array(
            'title' => 'Administration',
            'sub_title' => 'Types de soubassement'
        );

        $titreOnglet = "Type Soubassement";

        $object = 'TypeSoubassement';

        $entities = $this
            ->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeSoubassement')
            ->findAll();

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TypeSoubassement/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'object' => $object,
            'titreOnglet' => $titreOnglet
        ));
    }

}