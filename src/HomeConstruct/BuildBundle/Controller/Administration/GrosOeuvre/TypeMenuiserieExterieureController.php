<?php

namespace HomeConstruct\BuildBundle\Controller\Administration\GrosOeuvre;

use HomeConstruct\BuildBundle\Entity\TypeMenuiserieExterieure;
use HomeConstruct\BuildBundle\Form\TypeMenuiserieExterieureType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Type Menuiserie Exterieure controller.
 *
 * @Route("/administration/menuiserie-extérieure")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class TypeMenuiserieExterieureController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/", name="home_construct_type_menuiserie_exterieure_creation")
     * @Route("/edit/{idTypeMenuiserieExterieure}", name="home_construct_type_menuiserie_exterieure_edit")
     * @ParamConverter("typeMenuiserieExterieure", options={"mapping" : {"idTypeMenuiserieExterieure" : "id"}})
     */
    public function formAction(Request $request, TypeMenuiserieExterieure $typeMenuiserieExterieure=null){
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        if(!$typeMenuiserieExterieure){
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Ajout des menuiseries extérieures'
            );
            $titreOnglet = "Ajout des  menuiseries extérieures";
            $typeMenuiserieExterieure = new TypeMenuiserieExterieure();
        }else{
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Modification des menuiseries extérieures'
            );
            $titreOnglet = "Modif des  menuiseries extérieures";
        }
        $entities= $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeMenuiserieExterieure')
            ->findAll();
        $form = $this->createForm(TypeMenuiserieExterieureType::class, $typeMenuiserieExterieure);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!$typeMenuiserieExterieure->getId()){
                $notif='Type '.$typeMenuiserieExterieure->getNom().' ajouté';
            }else{
                $notif='Informations sur la menuiserie extérieure modifiées';
            }
            $em->persist($typeMenuiserieExterieure);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_type_menuiserie_exterieure_list');
        }

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TypeMenuiserieExterieure/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_type_menuiserie_exterieure_deleteone")
     */
    public function deleteOneAction(Request $request, TypeMenuiserieExterieure $typeMenuiserieExterieure)
    {
        $notif = $typeMenuiserieExterieure->getNom()." supprimé(e)";
        $em = $this->getDoctrine()->getManager();
        $em->remove($typeMenuiserieExterieure);
        $em->flush();
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_type_menuiserie_exterieure_list');
    }

    /**
     * @Route("/liste/", name="home_construct_type_menuiserie_exterieure_list")
     */
    public function listAction(Request $request)
    {
        $page = array(
            'title' => 'Administration',
            'sub_title' => 'Types de menuiserie extérieure'
        );

        $titreOnglet = "Type Menuiserie Exterieure";

        $object = 'TypeMenuiserieExterieure';

        $entities = $this
            ->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeMenuiserieExterieure')
            ->findAll();

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TypeMenuiserieExterieure/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'object' => $object,
            'titreOnglet' => $titreOnglet
        ));
    }

}