<?php

namespace HomeConstruct\BuildBundle\Controller\Administration\GrosOeuvre;

use HomeConstruct\BuildBundle\Entity\TypeIsolationPiece;
use HomeConstruct\BuildBundle\Form\MateriauxIsolationPieceType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Type IsolationPiece controller.
 *
 * @Route("/administration/type-isolation-piece")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class TypeIsolationPieceController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/", name="home_construct_type_isolationPiece_creation")
     * @Route("/edit/{idTypeIsolationPiece}", name="home_construct_type_isolationPiece_edit")
     * @ParamConverter("typeIsolationPiece", options={"mapping" : {"idTypeIsolationPiece" : "id"}})
     */
    public function formAction(Request $request, TypeIsolationPiece $typeIsolationPiece=null){
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        if(!$typeIsolationPiece){
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Création d\'un type d\'isolation de pièce'
            );
            $titreOnglet = "Ajout Type Isolation Piece";
            $typeIsolationPiece = new TypeIsolationPiece();
        }else{
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Modification d\'un type d\'isolation de pièce'
            );
            $titreOnglet = "Modif Type Isolation Piece";
        }
        $entities= $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeIsolationPiece')
            ->findAll();
        $form = $this->createForm(MateriauxIsolationPieceType::class, $typeIsolationPiece);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!$typeIsolationPiece->getId()){
                $notif='Type '.$typeIsolationPiece->getNom().' ajouté';
            }else{
                $notif='Type d\'isolation de pièce modifié';
            }
            $em->persist($typeIsolationPiece);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_type_isolationPiece_list');
        }

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TypeIsolationPiece/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_type_isolationPiece_deleteone")
     */
    public function deleteOneAction(Request $request, TypeIsolationPiece $typeIsolationPiece)
    {
        $notif = "Type ".$typeIsolationPiece->getNom()." supprimé";
        $em = $this->getDoctrine()->getManager();
        $em->remove($typeIsolationPiece);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_type_isolationPiece_list');
    }

    /**
     * @Route("/liste/", name="home_construct_type_isolationPiece_list")
     */
    public function listAction(Request $request)
    {
        $page = array(
            'title' => 'Administration',
            'sub_title' => 'Types d\'isolation de pièce'
        );

        $titreOnglet = "Type IsolationPiece";

        $object = 'TypeIsolationPiece';

        $entities = $this
            ->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeIsolationPiece')
            ->findAll();

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TypeIsolationPiece/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'object' => $object,
            'titreOnglet' => $titreOnglet
        ));
    }

}