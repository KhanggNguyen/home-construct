<?php

namespace HomeConstruct\BuildBundle\Controller\Administration\GrosOeuvre;

use HomeConstruct\BuildBundle\Entity\TypeAppuisFenetre;
use HomeConstruct\BuildBundle\Form\MateriauxAppuisFenetreType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Type AppuisFenetre controller.
 *
 * @Route("/administration/type-appui-fenêtre")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class TypeAppuisFenetreController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/", name="home_construct_type_appuisFenetre_creation")
     * @Route("/edit/{idTypeAppuisFenetre}", name="home_construct_type_appuisFenetre_edit")
     * @ParamConverter("typeAppuisFenetre", options={"mapping" : {"idTypeAppuisFenetre" : "id"}})
     */
    public function formAction(Request $request, TypeAppuisFenetre $typeAppuisFenetre=null){
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        if(!$typeAppuisFenetre){
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Création d\'un type d\'appui de fenêtre'
            );
            $titreOnglet = "Ajout Type Appui Fenêtre";
            $typeAppuisFenetre = new TypeAppuisFenetre();
        }else{
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Modification d\'un type d\'appui de fenêtre'
            );
            $titreOnglet = "Modif Type Appui Fenêtre";
        }
        $entities= $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeAppuisFenetre')
            ->findAll();
        $form = $this->createForm(MateriauxAppuisFenetreType::class, $typeAppuisFenetre);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!$typeAppuisFenetre->getId()){
                $notif='Type '.$typeAppuisFenetre->getNom().' ajouté';
            }else{
                $notif='Type d\'appui de fenêtre modifié';
            }
            $em->persist($typeAppuisFenetre);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_type_appuisFenetre_list');
        }
        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TypeAppuisFenetre/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_type_appuisFenetre_deleteone")
     */
    public function deleteOneAction(Request $request, TypeAppuisFenetre $typeAppuisFenetre)
    {
        $notif = 'Type '.$typeAppuisFenetre->getNom()." supprimé";
        $em = $this->getDoctrine()->getManager();
        $em->remove($typeAppuisFenetre);
        $em->flush();
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_type_appuisFenetre_list');
    }

    /**
     * @Route("/liste/", name="home_construct_type_appuisFenetre_list")
     */
    public function listAction(Request $request)
    {
        $page = array(
            'title' => 'Administration',
            'sub_title' => 'Types d\'appui de fenêtre'
        );

        $titreOnglet = "Type AppuisFenetre";

        $object = 'TypeAppuisFenetre';

        $entities = $this
            ->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeAppuisFenetre')
            ->findAll();

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TypeAppuisFenetre/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'object' => $object,
            'titreOnglet' => $titreOnglet
        ));
    }

}