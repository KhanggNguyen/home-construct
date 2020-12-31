<?php

namespace HomeConstruct\BuildBundle\Controller\Administration\SecondOeuvre;

use HomeConstruct\BuildBundle\Entity\TypeRevetementSol;
use HomeConstruct\BuildBundle\Form\MateriauxRevetementSolType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Type Revetement Sol controller.
 *
 * @Route("/administration/type-revetement-sol")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class TypeRevetementSolController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/", name="home_construct_type_revetement_sol_creation")
     * @Route("/edit/{idTypeRevetementSol}", name="home_construct_type_revetement_sol_edit")
     * @ParamConverter("typeRevetementSol", options={"mapping" : {"idTypeRevetementSol" : "id"}})
     */
    public function formAction(Request $request, TypeRevetementSol $typeRevetementSol=null){
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        if(!$typeRevetementSol){
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Création d\'un type de revêtement sol'
            );
            $titreOnglet = "Ajout Type Revetement Sol";
            $typeRevetementSol = new TypeRevetementSol();
        }else{
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Modification d\'un type de revêtement sol'
            );
            $titreOnglet = "Modif Type Revetement Sol";
        }
        $entities= $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeRevetementSol')
            ->findAll();
        $form = $this->createForm(MateriauxRevetementSolType::class, $typeRevetementSol);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!$typeRevetementSol->getId()){
                $notif='Type'. $typeRevetementSol->getNom(). 'ajouté';
            }else{
                $notif='Type de revetement sol modifié';
            }
            $em->persist($typeRevetementSol);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_type_revetement_sol_list');
        }

        return $this->render('@HomeConstructBuild/Administration/SecondOeuvre/TypeRevetementSol/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_type_revetement_sol_deleteone")
     */
    public function deleteOneAction(Request $request, TypeRevetementSol $typeRevetementSol)
    {
        $notif = $typeRevetementSol->getNom()." supprimé(e)";
        $em = $this->getDoctrine()->getManager();
        $em->remove($typeRevetementSol);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_type_revetement_sol_list');
    }

    /**
     * @Route("/liste/", name="home_construct_type_revetement_sol_list")
     */
    public function listAction(Request $request)
    {
        $page = array(
            'title' => 'Administration',
            'sub_title' => 'Types de revêtement sol'
        );

        $titreOnglet = "Type Revetement Sol";

        $object = 'TypeRevetementSol';

        $entities = $this
            ->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeRevetementSol')
            ->findAll();

        return $this->render('@HomeConstructBuild/Administration/SecondOeuvre/TypeRevetementSol/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'object' => $object,
            'titreOnglet' => $titreOnglet
        ));
    }

}