<?php

namespace HomeConstruct\BuildBundle\Controller\Administration\GrosOeuvre;

use HomeConstruct\BuildBundle\Entity\TypeIsolationMur;
use HomeConstruct\BuildBundle\Form\MateriauxIsolationMurType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Type IsolationMur controller.
 *
 * @Route("/administration/type-isolation-mur")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class TypeIsolationMurController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/", name="home_construct_type_isolationMur_creation")
     * @Route("/edit/{idTypeIsolationMur}", name="home_construct_type_isolationMur_edit")
     * @ParamConverter("typeIsolationMur", options={"mapping" : {"idTypeIsolationMur" : "id"}})
     */
    public function formAction(Request $request, TypeIsolationMur $typeIsolationMur=null){
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        if(!$typeIsolationMur){
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Création d\'un type d\'isolation des murs'
            );
            $titreOnglet = "Ajout Type Isolation Mur";
            $typeIsolationMur = new TypeIsolationMur();
        }else{
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Modification d\'un type d\'isolation des murs'
            );
            $titreOnglet = "Modif Type Isolation Mur";
        }
        $entities= $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeIsolationMur')
            ->findAll();
        $form = $this->createForm(MateriauxIsolationMurType::class, $typeIsolationMur);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!$typeIsolationMur->getId()){
                $notif='Type '.$typeIsolationMur->getNom().' ajouté';
            }else{
                $notif='Type d\'isolation modifié';
            }
            $em->persist($typeIsolationMur);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_type_isolationMur_list');
        }

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TypeIsolationMur/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_type_isolationMur_deleteone")
     */
    public function deleteOneAction(Request $request, TypeIsolationMur $typeIsolationMur)
    {
        $notif = "Type ".$typeIsolationMur->getNom()." supprimé(e)";
        $em = $this->getDoctrine()->getManager();
        $em->remove($typeIsolationMur);
        $em->flush();
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_type_isolationMur_list');
    }

    /**
     * @Route("/liste/", name="home_construct_type_isolationMur_list")
     */
    public function listAction(Request $request)
    {
        $page = array(
            'title' => 'Administration',
            'sub_title' => 'Types d\'isolation de mur'
        );

        $titreOnglet = "Type IsolationMur";

        $object = 'TypeIsolationMur';

        $entities = $this
            ->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeIsolationMur')
            ->findAll();

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TypeIsolationMur/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'object' => $object,
            'titreOnglet' => $titreOnglet
        ));
    }

}