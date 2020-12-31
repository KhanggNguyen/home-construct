<?php

namespace HomeConstruct\BuildBundle\Controller\Administration\GrosOeuvre;

use HomeConstruct\BuildBundle\Entity\TypeIsolationPlancher;
use HomeConstruct\BuildBundle\Form\MateriauxIsolationPlancherType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Type IsolationPlancher controller.
 *
 * @Route("/administration/type-isolation-plancher")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class TypeIsolationPlancherController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/", name="home_construct_type_isolationPlancher_creation")
     * @Route("/edit/{idTypeIsolationPlancher}", name="home_construct_type_isolationPlancher_edit")
     * @ParamConverter("typeIsolationPlancher", options={"mapping" : {"idTypeIsolationPlancher" : "id"}})
     */
    public function formAction(Request $request, TypeIsolationPlancher $typeIsolationPlancher=null){
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        if(!$typeIsolationPlancher){
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Création d\'un type d\'isolation de plancher'
            );
            $titreOnglet = "Ajout Type Isolation Plancher";
            $typeIsolationPlancher = new TypeIsolationPlancher();
        }else{
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Modification d\'un type d\'isolation de plancher'
            );
            $titreOnglet = "Modif Type Isolation Plancher";
        }
        $entities= $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeIsolationPlancher')
            ->findAll();
        $form = $this->createForm(MateriauxIsolationPlancherType::class, $typeIsolationPlancher);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!$typeIsolationPlancher->getId()){
                $notif='Type '.$typeIsolationPlancher->getNom().' ajouté';
            }else{
                $notif='Type d\'isolation de plancher modifié';
            }
            $em->persist($typeIsolationPlancher);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_type_isolationPlancher_list');
        }

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TypeIsolationPlancher/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_type_isolationPlancher_deleteone")
     */
    public function deleteOneAction(Request $request, TypeIsolationPlancher $typeIsolationPlancher)
    {
        $notif = 'Type '.$typeIsolationPlancher->getNom()." supprimé";
        $em = $this->getDoctrine()->getManager();
        $em->remove($typeIsolationPlancher);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_type_isolationPlancher_list');
    }

    /**
     * @Route("/liste/", name="home_construct_type_isolationPlancher_list")
     */
    public function listAction(Request $request)
    {
        $page = array(
            'title' => 'Administration',
            'sub_title' => 'Types d\'isolation de plancher'
        );

        $titreOnglet = "Type IsolationPlancher";

        $object = 'TypeIsolationPlancher';

        $entities = $this
            ->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeIsolationPlancher')
            ->findAll();

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TypeIsolationPlancher/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'object' => $object,
            'titreOnglet' => $titreOnglet
        ));
    }

}