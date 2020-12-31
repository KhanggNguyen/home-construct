<?php

namespace HomeConstruct\BuildBundle\Controller\Administration\GrosOeuvre;

use HomeConstruct\BuildBundle\Entity\TypeIsolationVitre;
use HomeConstruct\BuildBundle\Form\MateriauxIsolationVitreType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Type IsolationVitre controller.
 *
 * @Route("/administration/type-isolation-vitre")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class TypeIsolationVitreController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/", name="home_construct_type_isolationVitre_creation")
     * @Route("/edit/{idTypeIsolationVitre}", name="home_construct_type_isolationVitre_edit")
     * @ParamConverter("typeIsolationVitre", options={"mapping" : {"idTypeIsolationVitre" : "id"}})
     */
    public function formAction(Request $request, TypeIsolationVitre $typeIsolationVitre=null){
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        if(!$typeIsolationVitre){
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Création d\'un type d\'isolation des vitres'
            );
            $titreOnglet = "Ajout Type Isolation Vitre";
            $typeIsolationVitre = new TypeIsolationVitre();
        }else{
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Modification d\'un type d\'isolation des vitres'
            );
            $titreOnglet = "Modif Type Isolation Vitre";
        }
        $entities= $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeIsolationVitre')
            ->findAll();
        $form = $this->createForm(MateriauxIsolationVitreType::class, $typeIsolationVitre);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!$typeIsolationVitre->getId()){
                $notif='Type '.$typeIsolationVitre->getNom().' ajouté';
            }else{
                $notif='Type d\'isolation modifié';
            }
            $em->persist($typeIsolationVitre);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_type_isolationVitre_list');
        }

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TypeIsolationVitre/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_type_isolationVitre_deleteone")
     */
    public function deleteOneAction(Request $request, TypeIsolationVitre $typeIsolationVitre)
    {
        $notif = $typeIsolationVitre->getNom()." supprimé(e)";
        $em = $this->getDoctrine()->getManager();
        $em->remove($typeIsolationVitre);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_type_isolationVitre_list');
    }

    /**
     * @Route("/liste/", name="home_construct_type_isolationVitre_list")
     */
    public function listAction(Request $request)
    {
        $page = array(
            'title' => 'Administration',
            'sub_title' => 'Types d\'isolation de mur'
        );

        $titreOnglet = "Type IsolationVitre";

        $object = 'TypeIsolationVitre';

        $entities = $this
            ->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeIsolationVitre')
            ->findAll();

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TypeIsolationVitre/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'object' => $object,
            'titreOnglet' => $titreOnglet
        ));
    }

}