<?php

namespace HomeConstruct\BuildBundle\Controller\Administration\GrosOeuvre;

use HomeConstruct\BuildBundle\Entity\Travaux;
use HomeConstruct\BuildBundle\Form\TravauxType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * TypeTravauxTerrassement controller.
 *
 * @Route("/administration/type-travaux-terrassement")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class TypeTravauxTerrassementController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/", name="home_construct_travaux_creation")
     * @Route("/edit/{idTravaux}", name="home_construct_travaux_edit")
     * @ParamConverter("travaux", options={"mapping" : {"idTravaux" : "id"}})
     */
    public function formAction(Request $request, Travaux $travaux=null){
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        if(!$travaux){
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Création d\'un type de travaux pour le terrassement'
            );
            $titreOnglet = "Ajout Type Terrassement Terrassement";
            $travaux = new Travaux();
        }else{
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Modification d\'un type de travaux pour le terrassement'
            );
            $titreOnglet = "Modif Type Terrassement Terrassement";
        }
        $entities= $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:Travaux')
            ->findAll();
        $form = $this->createForm(TravauxType::class, $travaux);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!$travaux->getId()){
                $notif='Type '.$travaux->getNom().' ajouté';
            }else{
                $notif='Type de travaux modifié';
            }
            $em->persist($travaux);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_travaux_list');
        }

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TypeTravauxTerrassement/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_travaux_deleteone")
     */
    public function deleteOneAction(Request $request, Travaux $travaux)
    {
        $notif = $travaux->getNom()." supprimé(e)";
        $em = $this->getDoctrine()->getManager();
        $em->remove($travaux);
        $em->flush();
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_travaux_list');
    }

    /**
     * @Route("/liste/", name="home_construct_travaux_list")
     */
    public function listAction(Request $request)
    {
        $page = array(
            'title' => 'Administration',
            'sub_title' => 'Types de travaux pour le terrassement'
        );

        $titreOnglet = "Type Travaux Terrassement";


        $entities = $this
            ->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:Travaux')
            ->findAll();

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TypeTravauxTerrassement/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'titreOnglet' => $titreOnglet
        ));
    }

}