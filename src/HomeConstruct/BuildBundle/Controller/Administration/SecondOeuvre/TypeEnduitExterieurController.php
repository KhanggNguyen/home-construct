<?php

namespace HomeConstruct\BuildBundle\Controller\Administration\SecondOeuvre;

use HomeConstruct\BuildBundle\Entity\TypeEnduitExterieur;
use HomeConstruct\BuildBundle\Form\MateriauxEnduitExterieurType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Type EnduitExterieur controller.
 *
 * @Route("/administration/type-enduit-façade")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class TypeEnduitExterieurController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/", name="home_construct_type_enduitExterieur_creation")
     * @Route("/edit/{idTypeEnduitExterieur}", name="home_construct_type_enduitExterieur_edit")
     * @ParamConverter("typeEnduitExterieur", options={"mapping" : {"idTypeEnduitExterieur" : "id"}})
     */
    public function formAction(Request $request, TypeEnduitExterieur $typeEnduitExterieur=null){
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        if(!$typeEnduitExterieur){
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Création d\'un type d\'enduit de façade'
            );
            $titreOnglet = "Ajout Type Enduit Façade";
            $typeEnduitExterieur = new TypeEnduitExterieur();
        }else{
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Modification d\'un type d\'enduit de façade'
            );
            $titreOnglet = "Modif Type Enduit Façade";
        }
        $entities= $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeEnduitExterieur')
            ->findAll();
        $form = $this->createForm(MateriauxEnduitExterieurType::class, $typeEnduitExterieur);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!$typeEnduitExterieur->getId()){
                $notif='Type '.$typeEnduitExterieur->getNom().' ajouté';
            }else{
                $notif='Type d\'enduit de façade modifié';
            }
            $em->persist($typeEnduitExterieur);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_type_enduitExterieur_list');
        }

        return $this->render('@HomeConstructBuild/Administration/SecondOeuvre/TypeEnduitExterieur/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_type_enduitExterieur_deleteone")
     */
    public function deleteOneAction(Request $request, TypeEnduitExterieur $typeEnduitExterieur)
    {
        $notif = $typeEnduitExterieur->getNom()." supprimé(e)";
        $em = $this->getDoctrine()->getManager();
        $em->remove($typeEnduitExterieur);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_type_enduitExterieur_list');
    }

    /**
     * @Route("/liste/", name="home_construct_type_enduitExterieur_list")
     */
    public function listAction(Request $request)
    {
        $page = array(
            'title' => 'Administration',
            'sub_title' => 'Types d\'enduit de façade'
        );

        $titreOnglet = "Type Enduit Façade";

        $entities = $this
            ->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeEnduitExterieur')
            ->findAll();

        return $this->render('@HomeConstructBuild/Administration/SecondOeuvre/TypeEnduitExterieur/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'titreOnglet' => $titreOnglet
        ));
    }

}