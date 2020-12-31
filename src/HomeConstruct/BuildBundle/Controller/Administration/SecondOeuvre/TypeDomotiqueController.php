<?php

namespace HomeConstruct\BuildBundle\Controller\Administration\SecondOeuvre;

use HomeConstruct\BuildBundle\Entity\TypeDomotique;
use HomeConstruct\BuildBundle\Form\MateriauxDomotiqueType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Type Domotique controller.
 *
 * @Route("/administration/type-domotique")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class TypeDomotiqueController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/", name="home_construct_type_domotique_creation")
     * @Route("/edit/{idTypeDomotique}", name="home_construct_type_domotique_edit")
     * @ParamConverter("typeDomotique", options={"mapping" : {"idTypeDomotique" : "id"}})
     */
    public function formAction(Request $request, TypeDomotique $typeDomotique=null){
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        if(!$typeDomotique){
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Création d\'un type de domotique'
            );
            $titreOnglet = "Ajout Type Domotique";
            $typeDomotique = new TypeDomotique();
        }else{
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Modification d\'un type de domotique'
            );
            $titreOnglet = "Modif Type Domotique";
        }
        $entities= $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeDomotique')
            ->findAll();
        $form = $this->createForm(MateriauxDomotiqueType::class, $typeDomotique);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!$typeDomotique->getId()){
                $notif='Type '.$typeDomotique->getNom().' ajouté';
            }else{
                $notif='Type de domotique modifié';
            }
            $em->persist($typeDomotique);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_type_domotique_list');
        }

        return $this->render('@HomeConstructBuild/Administration/SecondOeuvre/TypeDomotique/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_type_domotique_deleteone")
     */
    public function deleteOneAction(Request $request, TypeDomotique $typeDomotique)
    {
        $notif = 'Type '.$typeDomotique->getNom()." supprimé(e)";
        $em = $this->getDoctrine()->getManager();
        $em->remove($typeDomotique);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_type_domotique_list');
    }

    /**
     * @Route("/liste/", name="home_construct_type_domotique_list")
     */
    public function listAction(Request $request)
    {
        $page = array(
            'title' => 'Administration',
            'sub_title' => 'Types de domotique'
        );

        $titreOnglet = "Type Domotique";

        $object = 'TypeDomotique';

        $entities = $this
            ->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeDomotique')
            ->findAll();

        return $this->render('@HomeConstructBuild/Administration/SecondOeuvre/TypeDomotique/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'object' => $object,
            'titreOnglet' => $titreOnglet
        ));
    }

}