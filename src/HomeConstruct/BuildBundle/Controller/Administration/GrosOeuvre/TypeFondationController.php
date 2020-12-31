<?php

namespace HomeConstruct\BuildBundle\Controller\Administration\GrosOeuvre;

use HomeConstruct\BuildBundle\Entity\TypeFondation;
use HomeConstruct\BuildBundle\Form\MateriauxFondationType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Type Fondation controller.
 *
 * @Route("/administration/type-fondation")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class TypeFondationController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/", name="home_construct_type_fondation_creation")
     * @Route("/edit/{idTypeFondation}", name="home_construct_type_fondation_edit")
     * @ParamConverter("typeFondation", options={"mapping" : {"idTypeFondation" : "id"}})
     */
    public function formAction(Request $request, TypeFondation $typeFondation=null){
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        if(!$typeFondation){
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Création d\'un type de fondation'
            );
            $titreOnglet = "Ajout Type Fondation";
            $typeFondation = new TypeFondation();
        }else{
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Modification d\'un type de fondation'
            );
            $titreOnglet = "Modif Type Fondation";
        }
        $entities= $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeFondation')
            ->findAll();
        $form = $this->createForm(MateriauxFondationType::class, $typeFondation);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!$typeFondation->getId()){
                $notif='Type '.$typeFondation->getNom().' ajouté';
            }else{
                $notif='Type de fondation modifié';
            }
            $em->persist($typeFondation);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_type_fondation_list');
        }
        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TypeFondation/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_type_fondation_deleteone")
     */
    public function deleteOneAction(Request $request, TypeFondation $typeFondation)
    {
        $notif = "Type ".$typeFondation->getNom()." supprimé(e)";
        $em = $this->getDoctrine()->getManager();
        $em->remove($typeFondation);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_type_fondation_list');
    }

    /**
     * @Route("/liste/", name="home_construct_type_fondation_list")
     */
    public function listAction(Request $request)
    {
        $page = array(
            'title' => 'Administration',
            'sub_title' => 'Types de fondation'
        );

        $titreOnglet = "Type Fondation";

        $entities = $this
            ->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeFondation')
            ->findAll();

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TypeFondation/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'titreOnglet' => $titreOnglet
        ));
    }

}