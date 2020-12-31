<?php

namespace HomeConstruct\BuildBundle\Controller\Administration\GrosOeuvre;

use HomeConstruct\BuildBundle\Entity\TypeIsolationPlafond;
use HomeConstruct\BuildBundle\Form\MateriauxIsolationPlafondType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Type IsolationPlafond controller.
 *
 * @Route("/administration/type-isolation-plafond")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class TypeIsolationPlafondController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/", name="home_construct_type_isolationPlafond_creation")
     * @Route("/edit/{idTypeIsolationPlafond}", name="home_construct_type_isolationPlafond_edit")
     * @ParamConverter("typeIsolationPlafond", options={"mapping" : {"idTypeIsolationPlafond" : "id"}})
     */
    public function formAction(Request $request, TypeIsolationPlafond $typeIsolationPlafond=null){
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        if(!$typeIsolationPlafond){
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Création d\'un type d\'isolation de plafond'
            );
            $titreOnglet = "Ajout Type Isolation Plafond";
            $typeIsolationPlafond = new TypeIsolationPlafond();
        }else{
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Modification d\'un type d\'isolation de plafond'
            );
            $titreOnglet = "Modif Type Isolation Plafond";
        }
        $entities= $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeIsolationPlafond')
            ->findAll();
        $form = $this->createForm(MateriauxIsolationPlafondType::class, $typeIsolationPlafond);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!$typeIsolationPlafond->getId()){
                $notif='Type '.$typeIsolationPlafond->getNom().' ajouté';
            }else{
                $notif='Type d\'isolation de plafond modifié';
            }
            $em->persist($typeIsolationPlafond);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_type_isolationPlafond_list');
        }

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TypeIsolationPlafond/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_type_isolationPlafond_deleteone")
     */
    public function deleteOneAction(Request $request, TypeIsolationPlafond $typeIsolationPlafond)
    {
        $notif = 'Type '.$typeIsolationPlafond->getNom()." supprimé(e)";
        $em = $this->getDoctrine()->getManager();
        $em->remove($typeIsolationPlafond);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_type_isolationPlafond_list');
    }

    /**
     * @Route("/liste/", name="home_construct_type_isolationPlafond_list")
     */
    public function listAction(Request $request)
    {
        $page = array(
            'title' => 'Administration',
            'sub_title' => 'Types d\'isolation de plafond'
        );

        $titreOnglet = "Type IsolationPlafond";

        $object = 'TypeIsolationPlafond';

        $entities = $this
            ->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeIsolationPlafond')
            ->findAll();

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TypeIsolationPlafond/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'object' => $object,
            'titreOnglet' => $titreOnglet
        ));
    }

}