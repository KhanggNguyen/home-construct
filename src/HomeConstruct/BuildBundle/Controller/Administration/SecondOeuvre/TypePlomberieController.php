<?php

namespace HomeConstruct\BuildBundle\Controller\Administration\SecondOeuvre;

use HomeConstruct\BuildBundle\Entity\TypePlomberie;
use HomeConstruct\BuildBundle\Form\TypePlomberieType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Type Plomberie controller.
 *
 * @Route("/administration/type-plomberie")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class TypePlomberieController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/", name="home_construct_type_plomberie_creation")
     * @Route("/edit/{idTypePlomberie}", name="home_construct_type_plomberie_edit")
     * @ParamConverter("typePlomberie", options={"mapping" : {"idTypePlomberie" : "id"}})
     */
    public function formAction(Request $request, TypePlomberie $typePlomberie=null){
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        if(!$typePlomberie){
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Création d\'un type plomberie'
            );
            $titreOnglet = "Ajout Type Plomberie";
            $typePlomberie = new TypePlomberie();
        }else{
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Modification d\'un type plomberie'
            );
            $titreOnglet = "Modif Type Plomberie";
        }
        $entities= $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypePlomberie')
            ->findAll();
        $form = $this->createForm(TypePlomberieType::class, $typePlomberie);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!$typePlomberie->getId()){
                $notif='Type '.$typePlomberie->getNom().' ajouté';
            }else{
                $notif='Type de plomberie modifié';
            }
            $em->persist($typePlomberie);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_type_plomberie_list');
        }

        return $this->render('@HomeConstructBuild/Administration/SecondOeuvre/TypePlomberie/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_type_plomberie_deleteone")
     */
    public function deleteOneAction(Request $request, TypePlomberie $typePlomberie)
    {
        $notif = 'Type '.$typePlomberie->getNom()." supprimé(e)";
        $em = $this->getDoctrine()->getManager();
        $em->remove($typePlomberie);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_type_plomberie_list');
    }

    /**
     * @Route("/liste/", name="home_construct_type_plomberie_list")
     */
    public function listAction(Request $request)
    {
        $page = array(
            'title' => 'Administration',
            'sub_title' => 'Types de plomberie'
        );

        $titreOnglet = "Type de Plomberie";

        $object = 'TypePlomberie';

        $entities = $this
            ->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypePlomberie')
            ->findAll();

        return $this->render('@HomeConstructBuild/Administration/SecondOeuvre/TypePlomberie/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'object' => $object,
            'titreOnglet' => $titreOnglet
        ));
    }

}