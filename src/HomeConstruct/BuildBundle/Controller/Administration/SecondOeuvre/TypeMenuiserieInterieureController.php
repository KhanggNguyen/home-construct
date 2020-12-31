<?php

namespace HomeConstruct\BuildBundle\Controller\Administration\SecondOeuvre;

use HomeConstruct\BuildBundle\Entity\TypeMenuiserieInterieure;
use HomeConstruct\BuildBundle\Form\TypeMenuiserieInterieureType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Type Menuiserie Interieure controller.
 *
 * @Route("/administration/type-menuiserie-interieure")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class TypeMenuiserieInterieureController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/", name="home_construct_type_menuiserie_interieure_creation")
     * @Route("/edit/{idTypeMenuiserieInterieure}", name="home_construct_type_menuiserie_interieure_edit")
     * @ParamConverter("typeMenuiserieInterieure", options={"mapping" : {"idTypeMenuiserieInterieure" : "id"}})
     */
    public function formAction(Request $request, TypeMenuiserieInterieure $typeMenuiserieInterieure=null){
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        if(!$typeMenuiserieInterieure){
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Création d\'un type de menuiserie intérieure'
            );
            $titreOnglet = "Ajout Type Menuiserie Interieure";
            $typeMenuiserieInterieure = new TypeMenuiserieInterieure();
        }else{
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Modification d\'un type de menuiserie intérieure'
            );
            $titreOnglet = "Modif Type Menuiserie Interieure";
        }
        $entities= $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeMenuiserieInterieure')
            ->findAll();
        $form = $this->createForm(TypeMenuiserieInterieureType::class, $typeMenuiserieInterieure);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!$typeMenuiserieInterieure->getId()){
                $notif='Type '.$typeMenuiserieInterieure->getNom().' ajouté';
            }else{
                $notif='Type de menuiserie intérieure modifié';
            }
            $em->persist($typeMenuiserieInterieure);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_type_menuiserie_interieure_list');
        }

        return $this->render('@HomeConstructBuild/Administration/SecondOeuvre/TypeMenuiserieInterieure/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_type_menuiserie_interieure_deleteone")
     */
    public function deleteOneAction(Request $request, TypeMenuiserieInterieure $typeMenuiserieInterieure)
    {
        $notif = "Type ".$typeMenuiserieInterieure->getNom()." supprimé";
        $em = $this->getDoctrine()->getManager();
        $em->remove($typeMenuiserieInterieure);
        $em->flush();
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_type_menuiserie_interieure_list');
    }

    /**
     * @Route("/liste/", name="home_construct_type_menuiserie_interieure_list")
     */
    public function listAction(Request $request)
    {
        $page = array(
            'title' => 'Administration',
            'sub_title' => 'Types de menuiserie intérieure'
        );

        $titreOnglet = "Type Menuiserie Interieure";

        $object = 'TypeMenuiserieInterieure';

        $entities = $this
            ->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeMenuiserieInterieure')
            ->findAll();

        return $this->render('@HomeConstructBuild/Administration/SecondOeuvre/TypeMenuiserieInterieure/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'object' => $object,
            'titreOnglet' => $titreOnglet
        ));
    }

}