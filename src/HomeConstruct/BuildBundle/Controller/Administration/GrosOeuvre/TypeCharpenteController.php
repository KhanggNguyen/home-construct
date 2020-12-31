<?php

namespace HomeConstruct\BuildBundle\Controller\Administration\GrosOeuvre;

use HomeConstruct\BuildBundle\Entity\TypeCharpente;
use HomeConstruct\BuildBundle\Form\MateriauxCharpenteType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Type Charpente controller.
 *
 * @Route("/administration/type-charpente")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class TypeCharpenteController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/", name="home_construct_type_charpente_creation")
     * @Route("/edit/{idTypeCharpente}", name="home_construct_type_charpente_edit")
     * @ParamConverter("typeCharpente", options={"mapping" : {"idTypeCharpente" : "id"}})
     */
    public function formAction(Request $request, TypeCharpente $typeCharpente=null){
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        if(!$typeCharpente){
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Création d\'un type de charpente'
            );
            $titreOnglet = "Ajout Type Charpente";
            $typeCharpente = new TypeCharpente();
        }else{
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Modification d\'un type de charpente'
            );
            $titreOnglet = "Modif Type Charpente";
        }
        $entities= $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeCharpente')
            ->findAll();
        $form = $this->createForm(MateriauxCharpenteType::class, $typeCharpente);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!$typeCharpente->getId()){
                $notif='Type '.$typeCharpente->getNom().' ajouté';
            }else{
                $notif='Type de charpente modifié';
            }
            $em->persist($typeCharpente);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_type_charpente_list');
        }

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TypeCharpente/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_type_charpente_deleteone")
     */
    public function deleteOneAction(Request $request, TypeCharpente $typeCharpente)
    {
        $notif = 'Type '.$typeCharpente->getNom()." supprimé";
        $em = $this->getDoctrine()->getManager();
        $em->remove($typeCharpente);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_type_charpente_list');
    }

    /**
     * @Route("/liste/", name="home_construct_type_charpente_list")
     */
    public function listAction(Request $request)
    {
        $page = array(
            'title' => 'Administration',
            'sub_title' => 'Types de charpente'
        );

        $titreOnglet = "Type Charpente";

        $object = 'TypeCharpente';

        $entities = $this
            ->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeCharpente')
            ->findAll();

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TypeCharpente/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'object' => $object,
            'titreOnglet' => $titreOnglet
        ));
    }

}