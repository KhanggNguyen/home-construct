<?php

namespace HomeConstruct\BuildBundle\Controller\Administration\GrosOeuvre;

use HomeConstruct\BuildBundle\Entity\TypeArbre;
use HomeConstruct\BuildBundle\Form\TypeArbreType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * TypeArbre controller.
 *
 * @Route("/administration/type-arbre")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class TypeArbreController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/", name="home_construct_type_arbre_creation")
     * @Route("/edit/{idTypeArbre}", name="home_construct_type_arbre_edit")
     * @ParamConverter("typeArbre", options={"mapping" : {"idTypeArbre" : "id"}})
     */
    public function formAction(Request $request, TypeArbre $typeArbre=null){
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        if(!$typeArbre){
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Création d\'un type d\'arbre'
            );
            $titreOnglet = "Ajout Type Arbre";
            $typeArbre = new TypeArbre();
        }else{
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Modification d\'un type d\'arbre'
            );
            $titreOnglet = "Modif Type Arbre";
        }
        $entities= $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeArbre')
            ->findAll();
        $form = $this->createForm(TypeArbreType::class, $typeArbre);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!$typeArbre->getId()){
                $notif='Type '.$typeArbre->getNom().' ajouté';
            }else{
                $notif='Type d\'arbre supprimé';
            }
            $em->persist($typeArbre);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_type_arbre_list');
        }

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TypeArbre/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_type_arbre_deleteone")
     */
    public function deleteOneAction(Request $request, TypeArbre $typeArbre)
    {
        $notif = "Type ".$typeArbre->getNom()." supprimé";
        $em = $this->getDoctrine()->getManager();
        $em->remove($typeArbre);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_type_arbre_list');
    }

    /**
     * @Route("/liste/", name="home_construct_type_arbre_list")
     */
    public function listAction(Request $request)
    {
        $page = array(
            'title' => 'Administration',
            'sub_title' => 'Types d\'arbres'
        );

        $titreOnglet = "TypeArbre";

        $object = 'TypeArbre';

        $entities = $this
            ->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeArbre')
            ->findAll();

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TypeArbre/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'object' => $object,
            'titreOnglet' => $titreOnglet
        ));
    }

}