<?php

namespace HomeConstruct\BuildBundle\Controller\Administration\SecondOeuvre;

use HomeConstruct\BuildBundle\Entity\TypeClimatisation;
use HomeConstruct\BuildBundle\Form\MateriauxClimatisationType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Type Climatisation controller.
 *
 * @Route("/administration/type-climatisation")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class TypeClimatisationController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/", name="home_construct_type_climatisation_creation")
     * @Route("/edit/{idTypeClimatisation}", name="home_construct_type_climatisation_edit")
     * @ParamConverter("typeClimatisation", options={"mapping" : {"idTypeClimatisation" : "id"}})
     */
    public function formAction(Request $request, TypeClimatisation $typeClimatisation=null){
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        if(!$typeClimatisation){
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Création d\'un type de climatisation'
            );
            $titreOnglet = "Ajout type climatisation";
            $typeClimatisation = new TypeClimatisation();
        }else{
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Modification d\'un type de climatisation'
            );
            $titreOnglet = "Modif type climatisation";
        }
        $entities= $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeClimatisation')
            ->findAll();
        $form = $this->createForm(MateriauxClimatisationType::class, $typeClimatisation);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!$typeClimatisation->getId()){
                $notif='Type '.$typeClimatisation->getNom(). ' ajouté';
            }else{
                $notif='Type de climatisation modifié';
            }
            $em->persist($typeClimatisation);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_type_climatisation_list');
        }

        return $this->render('@HomeConstructBuild/Administration/SecondOeuvre/TypeClimatisation/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_type_climatisation_deleteone")
     */
    public function deleteOneAction(Request $request, TypeClimatisation $typeClimatisation)
    {
        $notif = "Type ".$typeClimatisation->getNom()." supprimé(e)";
        $em = $this->getDoctrine()->getManager();
        $em->remove($typeClimatisation);
        $em->flush();
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_type_climatisation_list');
    }

    /**
     * @Route("/liste/", name="home_construct_type_climatisation_list")
     */
    public function listAction(Request $request)
    {
        $page = array(
            'title' => 'Administration',
            'sub_title' => 'Types de climatisation'
        );

        $titreOnglet = "Type Climatisation";

        $object = 'TypeClimatisation';

        $entities = $this
            ->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeClimatisation')
            ->findAll();

        return $this->render('@HomeConstructBuild/Administration/SecondOeuvre/TypeClimatisation/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'object' => $object,
            'titreOnglet' => $titreOnglet
        ));
    }

}