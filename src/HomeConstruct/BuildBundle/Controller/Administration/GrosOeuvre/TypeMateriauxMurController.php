<?php

namespace HomeConstruct\BuildBundle\Controller\Administration\GrosOeuvre;

use HomeConstruct\BuildBundle\Entity\TypeMateriauxMur;
use HomeConstruct\BuildBundle\Form\MateriauxMurType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Administration Materiaux Mur controller.
 *
 * @Route("/administration/materiaux-mur")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class TypeMateriauxMurController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/", name="home_construct_type_materiauxMur_creation")
     * @Route("/edit/{idTypeMateriauxMur}", name="home_construct_type_materiauxMur_edit")
     * @ParamConverter("typeMateriauxMur", options={"mapping" : {"idTypeMateriauxMur" : "id"}})
     */
    public function formAction(Request $request, TypeMateriauxMur $typeMateriauxMur=null){
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        if(!$typeMateriauxMur){
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Création d\'un matériau de mur'
            );
            $titreOnglet = "Ajout Materiau Mur";
            $typeMateriauxMur = new TypeMateriauxMur();
        }else{
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Modification d\'un matériau de mur'
            );
            $titreOnglet = "Modif Materiau Mur";
        }
        $entities= $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeMateriauxMur')
            ->findAll();
        $form = $this->createForm(MateriauxMurType::class, $typeMateriauxMur);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!$typeMateriauxMur->getId()){
                $notif='Type '.$typeMateriauxMur->getNom().' ajouté';
            }else{
                $notif='Materiau de mur modifié';
            }
            $em->persist($typeMateriauxMur);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_type_materiauxMur_list');
        }

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TypeMateriauxMur/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_type_materiauxMur_deleteone")
     */
    public function deleteOneAction(Request $request, TypeMateriauxMur $typeMateriauxMur)
    {
        $notif = 'Matériau "'.$typeMateriauxMur->getNom().'" supprimé';
        $em = $this->getDoctrine()->getManager();
        $em->remove($typeMateriauxMur);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_type_materiauxMur_list');
    }

    /**
     * @Route("/liste/", name="home_construct_type_materiauxMur_list")
     */
    public function listAction(Request $request)
    {
        $page = array(
            'title' => 'Administration',
            'sub_title' => 'Matériaux de mur'
        );

        $titreOnglet = "Type Materiaux Mur";

        $object = 'TypeMateriauxMur';

        $entities = $this
            ->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeMateriauxMur')
            ->findAll();

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TypeMateriauxMur/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'object' => $object,
            'titreOnglet' => $titreOnglet
        ));
    }

}