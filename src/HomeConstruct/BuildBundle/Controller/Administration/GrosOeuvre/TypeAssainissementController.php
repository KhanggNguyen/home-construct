<?php

namespace HomeConstruct\BuildBundle\Controller\Administration\GrosOeuvre;

use HomeConstruct\BuildBundle\Entity\Assainissement;
use HomeConstruct\BuildBundle\Form\AssainissementType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * TypeAssainissement controller.
 *
 * @Route("/administration/type-assainissement")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class TypeAssainissementController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/", name="home_construct_assainissement_creation")
     * @Route("/edit/{idAssainissement}", name="home_construct_assainissement_edit")
     * @ParamConverter("assainissement", options={"mapping" : {"idAssainissement" : "id"}})
     */
    public function formAction(Request $request, Assainissement $assainissement=null){
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        if(!$assainissement){
            $page = array(
                'title' => 'Matériaux',
                'sub_title' => 'Ajout du type d\'assainissement'
            );
            $titreOnglet = "Ajout type assainissement";
            $assainissement = new Assainissement();
        }else{
            $page = array(
                'title' => 'Matériaux',
                'sub_title' => 'Modification du type d\'assainissement'
            );
            $titreOnglet = "Modif type assainissement";
        }
        $entities= $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:Assainissement')
            ->findAll();
        $form = $this->createForm(AssainissementType::class, $assainissement);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!$assainissement->getId()){
                $notif='Type d\'assainissement ajouté';
            }else{
                $notif='Type d\'assainissement modifié';
            }
            $em->persist($assainissement);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_assainissement_list');
        }

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TypeAssainissement/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_assainissement_deleteone")
     */
    public function deleteOneAction(Request $request, Assainissement $assainissement)
    {
        $notif = "Type ".$assainissement->getNom()." supprimé(e)";
        $em = $this->getDoctrine()->getManager();
        $em->remove($assainissement);
        $em->flush();
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_assainissement_list');
    }

    /**
     * @Route("/liste/", name="home_construct_assainissement_list")
     */
    public function listAction(Request $request)
    {
        $page = array(
            'title' => 'Administration',
            'sub_title' => 'Types assainissement'
        );

        $titreOnglet = "Type Assainissement";

        $object = 'Type Assainissement';

        $entities = $this
            ->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:Assainissement')
            ->findAll();

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TypeAssainissement/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'object' => $object,
            'titreOnglet' => $titreOnglet
        ));
    }

}