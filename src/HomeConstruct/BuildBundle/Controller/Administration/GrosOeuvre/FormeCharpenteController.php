<?php

namespace HomeConstruct\BuildBundle\Controller\Administration\GrosOeuvre;

use HomeConstruct\BuildBundle\Entity\FormeCharpente;
use HomeConstruct\BuildBundle\Form\MateriauxFormeCharpenteType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * FormeCharpente controller.
 *
 * @Route("/administration/forme-charpente")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class FormeCharpenteController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/", name="home_construct_formeCharpente_creation")
     * @Route("/edit/{idFormeCharpente}", name="home_construct_formeCharpente_edit")
     * @ParamConverter("FormeCharpente", options={"mapping" : {"idFormeCharpente" : "id"}})
     */
    public function formAction(Request $request, FormeCharpente $formeCharpente=null){
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        if(!$formeCharpente){
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Création d\'une forme de charpente'
            );
            $titreOnglet = "Ajout Forme Charpente";
            $formeCharpente = new FormeCharpente();
        }else{
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Modification d\'une forme de charpente'
            );
            $titreOnglet = "Modif Forme Charpente";
        }
        $entities= $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:FormeCharpente')
            ->findAll();
        $form = $this->createForm(MateriauxFormeCharpenteType::class, $formeCharpente);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!$formeCharpente->getId()){
                $notif='Forme "'.$formeCharpente->getNom().'" ajouté';
            }else{
                $notif='Forme de charpente modifié';
            }
            $em->persist($formeCharpente);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_formeCharpente_list');
        }

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/FormeCharpente/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_formeCharpente_deleteone")
     */
    public function deleteOneAction(Request $request, FormeCharpente $formeCharpente)
    {
        $notif = 'Forme "'.$formeCharpente->getNom().'" supprimé(';
        $em = $this->getDoctrine()->getManager();
        $em->remove($formeCharpente);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_formeCharpente_list');
    }

    /**
     * @Route("/liste/", name="home_construct_formeCharpente_list")
     */
    public function listAction(Request $request)
    {
        $page = array(
            'title' => 'Administration',
            'sub_title' => 'Formes de charpente'
        );

        $titreOnglet = "FormeCharpente";

        $object = 'FormeCharpente';

        $entities = $this
            ->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:FormeCharpente')
            ->findAll();

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/FormeCharpente/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'object' => $object,
            'titreOnglet' => $titreOnglet
        ));
    }

}