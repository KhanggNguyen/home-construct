<?php

namespace HomeConstruct\BuildBundle\Controller\Administration\GrosOeuvre;

use HomeConstruct\BuildBundle\Entity\TypeSol;
use HomeConstruct\BuildBundle\Form\MateriauxTypeSolType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * TypeSol controller.
 *
 * @Route("/administration/type-sol")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class TypeSolController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/", name="home_construct_type_sol_creation")
     * @Route("/edit/{idTypeSol}", name="home_construct_type_sol_edit")
     * @ParamConverter("typeSol", options={"mapping" : {"idTypeSol" : "id"}})
     */
    public function formAction(Request $request, TypeSol $typeSol=null){
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        if(!$typeSol){
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Création d\'un type de sol'
            );
            $titreOnglet = "Ajout Type Sol";
            $typeSol = new TypeSol();
        }else{
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Modification d\'u type de sol'
            );
            $titreOnglet = "Modif Type Sol";
        }
        $entities= $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeSol')
            ->findAll();
        $form = $this->createForm(MateriauxTypeSolType::class, $typeSol);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!$typeSol->getId()){
                $notif='Type'. $typeSol->getNom(). 'ajouté';
            }else{
                $notif='Type de sol modifié';
            }
            $em->persist($typeSol);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_type_sol_list');
        }

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TypeSol/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_type_sol_deleteone")
     */
    public function deleteOneAction(Request $request, TypeSol $typeSol)
    {
        $notif = $typeSol->getNom()." supprimé(e)";
        $em = $this->getDoctrine()->getManager();
        $em->remove($typeSol);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_type_sol_list');
    }

    /**
     * @Route("/liste/", name="home_construct_type_sol_list")
     */
    public function listAction(Request $request)
    {
        $page = array(
            'title' => 'Administration',
            'sub_title' => 'Types de sol'
        );

        $titreOnglet = "Type Sol";

        $object = 'TypeSol';

        $entities = $this
            ->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeSol')
            ->findAll();

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TypeSol/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'object' => $object,
            'titreOnglet' => $titreOnglet
        ));
    }

}