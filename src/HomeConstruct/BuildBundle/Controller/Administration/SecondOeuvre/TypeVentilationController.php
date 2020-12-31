<?php

namespace HomeConstruct\BuildBundle\Controller\Administration\SecondOeuvre;

use HomeConstruct\BuildBundle\Entity\TypeVentilation;
use HomeConstruct\BuildBundle\Form\TypeVentilationType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * TypeVentilation controller.
 *
 * @Route("/administration/type-ventilation")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class TypeVentilationController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/", name="home_construct_type_ventilation_creation")
     * @Route("/edit/{idTypeVentilation}", name="home_construct_type_ventilation_edit")
     * @ParamConverter("typeVentilation", options={"mapping" : {"idTypeVentilation" : "id"}})
     */
    public function formAction(Request $request, TypeVentilation $typeVentilation=null){
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        if(!$typeVentilation){
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Création d\'un type de ventilation'
            );
            $titreOnglet = "Ajout Type Ventilation";
            $typeVentilation = new TypeVentilation();
        }else{
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Modification d\'un type ventilation'
            );
            $titreOnglet = "Modif Type Ventilation";
        }
        $entities= $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeVentilation')
            ->findAll();
        $form = $this->createForm(TypeVentilationType::class, $typeVentilation);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!$typeVentilation->getId()){
                $notif='Type'.$typeVentilation->getNom().'ajouté';
            }else{
                $notif='Type de ventilation modifié';
            }
            $em->persist($typeVentilation);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_type_ventilation_list');
        }

        return $this->render('@HomeConstructBuild/Administration/SecondOeuvre/TypeVentilation/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_type_ventilation_deleteone")
     */
    public function deleteOneAction(Request $request, TypeVentilation $typeVentilation)
    {
        $notif = $typeVentilation->getNom()." supprimé(e)";
        $em = $this->getDoctrine()->getManager();
        $em->remove($typeVentilation);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_type_ventilation_list');
    }

    /**
     * @Route("/liste/", name="home_construct_type_ventilation_list")
     */
    public function listAction(Request $request)
    {
        $page = array(
            'title' => 'Administration',
            'sub_title' => 'Types de ventilation'
        );

        $titreOnglet = "Type Ventilation";

        $object = 'TypeVentilation';

        $entities = $this
            ->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeVentilation')
            ->findAll();

        return $this->render('@HomeConstructBuild/Administration/SecondOeuvre/TypeVentilation/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'object' => $object,
            'titreOnglet' => $titreOnglet
        ));
    }

}