<?php

namespace HomeConstruct\BuildBundle\Controller\Administration\GrosOeuvre;

use HomeConstruct\BuildBundle\Entity\TypePlancher;
use HomeConstruct\BuildBundle\Form\MateriauxPlancherType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Type Plancher controller.
 *
 * @Route("/administration/type-plancher")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class TypePlancherController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/", name="home_construct_type_plancher_creation")
     * @Route("/edit/{idTypePlancher}", name="home_construct_type_plancher_edit")
     * @ParamConverter("typePlancher", options={"mapping" : {"idTypePlancher" : "id"}})
     */
    public function formAction(Request $request, TypePlancher $typePlancher=null){
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        if(!$typePlancher){
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Création d\'un type plancher'
            );
            $titreOnglet = "Ajout Type Plancher";
            $typePlancher = new TypePlancher();
        }else{
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Modification d\'un type de plancher'
            );
            $titreOnglet = "Modif Type Plancher";
        }
        $entities= $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypePlancher')
            ->findAll();
        $form = $this->createForm(MateriauxPlancherType::class, $typePlancher);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!$typePlancher->getId()){
                $notif='Type '.$typePlancher->getNom().' ajouté';
            }else{
                $notif='Type de plancher modifié';
            }
            $em->persist($typePlancher);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_type_plancher_list');
        }

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TypePlancher/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_type_plancher_deleteone")
     */
    public function deleteOneAction(Request $request, TypePlancher $typePlancher)
    {
        $notif = "Type ".$typePlancher->getNom()." supprimé(e)";
        $em = $this->getDoctrine()->getManager();
        $em->remove($typePlancher);
        $em->flush();
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_type_plancher_list');
    }

    /**
     * @Route("/liste/", name="home_construct_type_plancher_list")
     */
    public function listAction(Request $request)
    {
        $page = array(
            'title' => 'Administration',
            'sub_title' => 'Types de plancher'
        );

        $titreOnglet = "Type Plancher";

        $object = 'TypePlancher';

        $entities = $this
            ->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypePlancher')
            ->findAll();

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TypePlancher/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'object' => $object,
            'titreOnglet' => $titreOnglet
        ));
    }

}