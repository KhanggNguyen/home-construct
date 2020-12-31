<?php

namespace HomeConstruct\BuildBundle\Controller\Administration\SecondOeuvre;

use HomeConstruct\BuildBundle\Entity\MateriauxEscalier;
use HomeConstruct\BuildBundle\Form\MateriauxEscalierType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Administration Escalier controller.
 *
 * @Route("/administration/materiaux-escalier")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class MateriauxEscalierController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/", name="home_construct_materiaux_escalier_creation")
     * @Route("/edit/{idMateriauxEscalier}", name="home_construct_materiaux_escalier_edit")
     * @ParamConverter("materiauxEscalier", options={"mapping" : {"idMateriauxEscalier" : "id"}})
     */
    public function formAction(Request $request, MateriauxEscalier $materiauxEscalier=null){
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        if(!$materiauxEscalier){
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Création d\'un matériau d\'escalier'
            );
            $titreOnglet = "Ajout Matériau Escalier";
            $materiauxEscalier = new MateriauxEscalier();
        }else{
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Modification d\'un matériau d\'escalier'
            );
            $titreOnglet = "Modif Matériaux Escalier";
        }
        $entities= $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:MateriauxEscalier')
            ->findAll();
        $form = $this->createForm(MateriauxEscalierType::class, $materiauxEscalier);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!$materiauxEscalier->getId()){
                $notif='Matériau d\'escalier ajouté';
            }else{
                $notif='Matériau d\'escalier modifié';
            }
            $em->persist($materiauxEscalier);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_materiaux_escalier_list');
        }

        return $this->render('@HomeConstructBuild/Administration/SecondOeuvre/MateriauxEscalier/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_materiaux_escalier_deleteone")
     */
    public function deleteOneAction(Request $request, MateriauxEscalier $materiauxEscalier)
    {
        $notif = $materiauxEscalier->getNom()." supprimé(e)";
        $em = $this->getDoctrine()->getManager();
        $em->remove($materiauxEscalier);
        $em->flush();
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_materiaux_escalier_list');
    }

    /**
     * @Route("/liste/", name="home_construct_materiaux_escalier_list")
     */
    public function listAction(Request $request)
    {
        $page = array(
            'title' => 'Administration',
            'sub_title' => 'Matériaux des escaliers'
        );

        $titreOnglet = "Liste Matériaux Escalier";

        $object = 'MateriauxEscalier';

        $entities = $this
            ->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:MateriauxEscalier')
            ->findAll();

        return $this->render('@HomeConstructBuild/Administration/SecondOeuvre/MateriauxEscalier/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'object' => $object,
            'titreOnglet' => $titreOnglet
        ));
    }

}