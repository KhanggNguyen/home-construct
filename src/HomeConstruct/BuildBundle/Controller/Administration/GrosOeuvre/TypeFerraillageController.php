<?php

namespace HomeConstruct\BuildBundle\Controller\Administration\GrosOeuvre;

use HomeConstruct\BuildBundle\Entity\TypeFerraillage;
use HomeConstruct\BuildBundle\Form\MateriauxFerraillageType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Type Ferraillage controller.
 *
 * @Route("/administration/type-ferraillage")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class TypeFerraillageController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/", name="home_construct_type_ferraillage_creation")
     * @Route("/edit/{idTypeFerraillage}", name="home_construct_type_ferraillage_edit")
     * @ParamConverter("typeFerraillage", options={"mapping" : {"idTypeFerraillage" : "id"}})
     */
    public function formAction(Request $request, TypeFerraillage $typeFerraillage=null){
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        if(!$typeFerraillage){
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Création d\'un type de ferraillage'
            );
            $titreOnglet = "Ajout Type Ferraillage";
            $typeFerraillage = new TypeFerraillage();
        }else{
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Modification d\'un type de ferraillage'
            );
            $titreOnglet = "Modif Type Ferraillage";
        }
        $entities= $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeFerraillage')
            ->findAll();
        $form = $this->createForm(MateriauxFerraillageType::class, $typeFerraillage);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!$typeFerraillage->getId()){
                $notif='Type '.$typeFerraillage->getNom().' ajouté';
            }else{
                $notif='Type de ferraillage modifié';
            }
            $em->persist($typeFerraillage);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_type_ferraillage_list');
        }

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TypeFerraillage/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_type_ferraillage_deleteone")
     */
    public function deleteOneAction(Request $request, TypeFerraillage $typeFerraillage)
    {
        $notif = 'Type '.$typeFerraillage->getNom()." supprimé";
        $em = $this->getDoctrine()->getManager();
        $em->remove($typeFerraillage);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_type_ferraillage_list');
    }

    /**
     * @Route("/liste/", name="home_construct_type_ferraillage_list")
     */
    public function listAction(Request $request)
    {
        $page = array(
            'title' => 'Administration',
            'sub_title' => 'Types de ferraillage'
        );

        $titreOnglet = "Type Ferraillage";

        $object = 'TypeFerraillage';

        $entities = $this
            ->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeFerraillage')
            ->findAll();

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TypeFerraillage/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'object' => $object,
            'titreOnglet' => $titreOnglet
        ));
    }

}