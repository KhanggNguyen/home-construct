<?php

namespace HomeConstruct\BuildBundle\Controller\Administration\SecondOeuvre;

use HomeConstruct\BuildBundle\Entity\TypeChauffage;
use HomeConstruct\BuildBundle\Form\MateriauxChauffageType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Type Chauffage controller.
 *
 * @Route("/administration/type-chauffage")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class TypeChauffageController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/", name="home_construct_type_chauffage_creation")
     * @Route("/edit/{idTypeChauffage}", name="home_construct_type_chauffage_edit")
     * @ParamConverter("typeChauffage", options={"mapping" : {"idTypeChauffage" : "id"}})
     */
    public function formAction(Request $request, TypeChauffage $typeChauffage=null){
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        if(!$typeChauffage){
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Création d\'un type de chauffage'
            );
            $titreOnglet = "Ajout Type Chauffage";
            $typeChauffage = new TypeChauffage();
        }else{
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Modification d\'un type de chauffage'
            );
            $titreOnglet = "Modif Type Chauffage";
        }
        $entities= $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeChauffage')
            ->findAll();
        $form = $this->createForm(MateriauxChauffageType::class, $typeChauffage);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!$typeChauffage->getId()){
                $notif='Type '.$typeChauffage->getNom().' ajouté';
            }else{
                $notif='Type de chauffage modifié';
            }
            $em->persist($typeChauffage);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_type_chauffage_list');
        }

        return $this->render('@HomeConstructBuild/Administration/SecondOeuvre/TypeChauffage/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_type_chauffage_deleteone")
     */
    public function deleteOneAction(Request $request, TypeChauffage $typeChauffage)
    {
        $notif = 'Type '.$typeChauffage->getNom()." supprimé(e)";
        $em = $this->getDoctrine()->getManager();
        $em->remove($typeChauffage);
        $em->flush();
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_type_chauffage_list');
    }

    /**
     * @Route("/liste/", name="home_construct_type_chauffage_list")
     */
    public function listAction(Request $request)
    {
        $page = array(
            'title' => 'Administration',
            'sub_title' => 'Types de chauffage'
        );

        $titreOnglet = "Type Chauffage";

        $object = 'TypeChauffage';

        $entities = $this
            ->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeChauffage')
            ->findAll();

        return $this->render('@HomeConstructBuild/Administration/SecondOeuvre/TypeChauffage/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'object' => $object,
            'titreOnglet' => $titreOnglet
        ));
    }

}