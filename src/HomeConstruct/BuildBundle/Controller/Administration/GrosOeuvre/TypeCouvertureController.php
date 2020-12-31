<?php

namespace HomeConstruct\BuildBundle\Controller\Administration\GrosOeuvre;

use HomeConstruct\BuildBundle\Entity\TypeCharpente;
use HomeConstruct\BuildBundle\Entity\TypeCouverture;
use HomeConstruct\BuildBundle\Form\TypeCouvertureType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Type Couverture controller.
 *
 * @Route("/administration/type-couverture-toiture")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class TypeCouvertureController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/", name="home_construct_type_couverture_creation")
     * @Route("/edit/{idTypeCouverture}", name="home_construct_type_couverture_edit")
     * @ParamConverter("typeCouverture", options={"mapping" : {"idTypeCouverture" : "id"}})
     */
    public function formAction(Request $request, TypeCouverture $typeCouverture=null){
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        if(!$typeCouverture){
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Création d\'un type de couverture de toiture'
            );
            $titreOnglet = "Ajout Type Couverture";
            $typeCouverture = new TypeCouverture();
        }else{
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Modification d\'un type de couverture de toiture'
            );
            $titreOnglet = "Modif Type Couverture";
        }
        $entities= $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeCouverture')
            ->findAll();
        $form = $this->createForm(TypeCouvertureType::class, $typeCouverture);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!$typeCouverture->getId()){
                $notif='Type '.$typeCouverture->getNom().' ajouté';
            }else{
                $notif='Type de couverture modifié';
            }
            $em->persist($typeCouverture);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_type_couverture_list');
        }

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TypeCouverture/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_type_couverture_deleteone")
     */
    public function deleteOneAction(Request $request, TypeCouverture $typeCouverture)
    {
        $notif = 'Type '.$typeCouverture->getNom()." supprimé";
        $em = $this->getDoctrine()->getManager();
        $em->remove($typeCouverture);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_type_couverture_list');
    }

    /**
     * @Route("/liste/", name="home_construct_type_couverture_list")
     */
    public function listAction(Request $request)
    {
        $page = array(
            'title' => 'Administration',
            'sub_title' => 'Types de couverture de toiture'
        );

        $titreOnglet = "Type Couverture";

        $entities = $this
            ->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeCouverture')
            ->findAll();

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TypeCouverture/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'titreOnglet' => $titreOnglet
        ));
    }

}