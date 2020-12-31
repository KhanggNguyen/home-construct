<?php

namespace HomeConstruct\BuildBundle\Controller\Administration\GrosOeuvre;

use HomeConstruct\BuildBundle\Entity\TypeMateriauxPoutre;
use HomeConstruct\BuildBundle\Form\MateriauxPoutreType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Type MateriauxPoutre controller.
 *
 * @Route("/administration/materiaux-poutre")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class TypeMateriauxPoutreController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/", name="home_construct_type_materiauxPoutre_creation")
     * @Route("/edit/{idTypeMateriauxPoutre}", name="home_construct_type_materiauxPoutre_edit")
     * @ParamConverter("typeMateriauxPoutre", options={"mapping" : {"idTypeMateriauxPoutre" : "id"}})
     */
    public function formAction(Request $request, TypeMateriauxPoutre $typeMateriauxPoutre=null){
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        if(!$typeMateriauxPoutre){
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Création d\'un matériau de poutre'
            );
            $titreOnglet = "Ajout Materiau Poutre";
            $typeMateriauxPoutre = new TypeMateriauxPoutre();
        }else{
            $page = array(
                'title' => 'Administration ',
                'sub_title' => 'Modification d\'un matériau de poutre'
            );
            $titreOnglet = "Modif Materiau Poutre";
        }
        $entities= $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeMateriauxPoutre')
            ->findAll();
        $form = $this->createForm(MateriauxPoutreType::class, $typeMateriauxPoutre);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!$typeMateriauxPoutre->getId()){
                $notif='Type '.$typeMateriauxPoutre->getNom().' ajouté';
            }else{
                $notif='Matériau de poutre modifié';
            }
            $em->persist($typeMateriauxPoutre);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_type_materiauxPoutre_list');
        }

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TypeMateriauxPoutre/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_type_materiauxPoutre_deleteone")
     */
    public function deleteOneAction(Request $request, TypeMateriauxPoutre $typeMateriauxPoutre)
    {
        $notif = $typeMateriauxPoutre->getNom()." supprimé(e)";
        $em = $this->getDoctrine()->getManager();
        $em->remove($typeMateriauxPoutre);
        $em->flush();
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_type_materiauxPoutre_list');
    }

    /**
     * @Route("/liste/", name="home_construct_type_materiauxPoutre_list")
     */
    public function listAction(Request $request)
    {
        $page = array(
            'title' => 'Administration',
            'sub_title' => 'Matériaux de poutre'
        );

        $titreOnglet = "Type Materiaux Poutre";

        $object = 'TypeMateriauxPoutre';

        $entities = $this
            ->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeMateriauxPoutre')
            ->findAll();

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TypeMateriauxPoutre/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'object' => $object,
            'titreOnglet' => $titreOnglet
        ));
    }

}