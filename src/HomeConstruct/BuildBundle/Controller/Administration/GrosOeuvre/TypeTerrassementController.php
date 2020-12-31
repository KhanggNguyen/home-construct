<?php

namespace HomeConstruct\BuildBundle\Controller\Administration\GrosOeuvre;

use HomeConstruct\BuildBundle\Entity\TypeTerrassement;
use HomeConstruct\BuildBundle\Form\TypeTerrassementType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Leafo\ScssPhp\Type;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * TypeTerrassement controller.
 *
 * @Route("/administration/type-terrassement")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class TypeTerrassementController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/", name="home_construct_type_terrassement_creation")
     * @Route("/edit/{idTypeTerrassement}", name="home_construct_type_terrassement_edit")
     * @ParamConverter("typeTerrassement", options={"mapping" : {"idTypeTerrassement" : "id"}})
     */
    public function formAction(Request $request, TypeTerrassement $typeTerrassement=null){
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        if(!$typeTerrassement){
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Création d\'un type de terrassement'
            );
            $titreOnglet = "Ajout Type Terrassement";
            $typeTerrassement = new TypeTerrassement();
        }else{
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Modification d\'un type de terrassement'
            );
            $titreOnglet = "Modif Type Terrassement";
        }
        $entities= $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeTerrassement')
            ->findAll();
        $form = $this->createForm(TypeTerrassementType::class, $typeTerrassement);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!$typeTerrassement->getId()){
                $notif='Type'.$typeTerrassement->getNom().'ajouté';
            }else{
                $notif='Type de terrassement modifié';
            }
            $em->persist($typeTerrassement);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_type_terrassement_list');
        }

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TypeTerrassement/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_type_terrassement_deleteone")
     */
    public function deleteOneAction(Request $request, TypeTerrassement $typeTerrassement)
    {
        $notif = $typeTerrassement->getNom()." supprimé(e)";
        $em = $this->getDoctrine()->getManager();
        $em->remove($typeTerrassement);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_type_terrassement_list');
    }

    /**
     * @Route("/liste/", name="home_construct_type_terrassement_list")
     */
    public function listAction(Request $request)
    {
        $page = array(
            'title' => 'Administration',
            'sub_title' => 'Types de terrassement'
        );

        $titreOnglet = "Type Terrassement";

        $object = 'TypeTerrassement';

        $entities = $this
            ->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeTerrassement')
            ->findAll();

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TypeTerrassement/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'object' => $object,
            'titreOnglet' => $titreOnglet
        ));
    }

}