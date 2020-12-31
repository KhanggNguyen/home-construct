<?php

namespace HomeConstruct\BuildBundle\Controller\Administration\SecondOeuvre;

use HomeConstruct\BuildBundle\Entity\TypeEvacuation;
use HomeConstruct\BuildBundle\Form\MateriauxEvacuationType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Type Evacuation controller.
 *
 * @Route("/administration/type-evacuation")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class TypeEvacuationController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/", name="home_construct_type_evacuation_creation")
     * @Route("/edit/{idTypeEvacuation}", name="home_construct_type_evacuation_edit")
     * @ParamConverter("typeEvacuation", options={"mapping" : {"idTypeEvacuation" : "id"}})
     */
    public function formAction(Request $request, TypeEvacuation $typeEvacuation=null){
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        if(!$typeEvacuation){
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Création d\'un type d\'évacuation'
            );
            $titreOnglet = "Ajout Type Evacuation";
            $typeEvacuation = new TypeEvacuation();
        }else{
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Modification d\'un type d\'évacuation'
            );
            $titreOnglet = "Modif Type Evacuation";
        }
        $entities= $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeEvacuation')
            ->findAll();
        $form = $this->createForm(MateriauxEvacuationType::class, $typeEvacuation);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!$typeEvacuation->getId()){
                $notif='Type '.$typeEvacuation->getNom().' ajouté';
            }else{
                $notif='Type d\'évacuation modifié';
            }
            $em->persist($typeEvacuation);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_type_evacuation_list');
        }

        return $this->render('@HomeConstructBuild/Administration/SecondOeuvre/TypeEvacuation/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_type_evacuation_deleteone")
     */
    public function deleteOneAction(Request $request, TypeEvacuation $typeEvacuation)
    {
        $notif = 'Type '.$typeEvacuation->getNom()." supprimé(e)";
        $em = $this->getDoctrine()->getManager();
        $em->remove($typeEvacuation);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_type_evacuation_list');
    }

    /**
     * @Route("/liste/", name="home_construct_type_evacuation_list")
     */
    public function listAction(Request $request)
    {
        $page = array(
            'title' => 'Administration',
            'sub_title' => 'Types d\'évacuations'
        );

        $titreOnglet = "Type Evacuation";

        $object = 'TypeEvacuation';

        $entities = $this
            ->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeEvacuation')
            ->findAll();

        return $this->render('@HomeConstructBuild/Administration/SecondOeuvre/TypeEvacuation/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'object' => $object,
            'titreOnglet' => $titreOnglet
        ));
    }

}