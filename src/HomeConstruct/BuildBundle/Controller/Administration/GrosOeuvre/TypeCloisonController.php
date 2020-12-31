<?php

namespace HomeConstruct\BuildBundle\Controller\Administration\GrosOeuvre;

use HomeConstruct\BuildBundle\Entity\Cloison;
use HomeConstruct\BuildBundle\Form\CloisonType;
use HomeConstruct\BuildBundle\Form\TypeCloisonType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * TypeCloison controller.
 *
 * @Route("/administration/type-cloison")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class TypeCloisonController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/", name="home_construct_cloison_creation")
     * @Route("/edit/{idCloison}", name="home_construct_cloison_edit")
     * @ParamConverter("cloison", options={"mapping" : {"idCloison" : "id"}})
     */
    public function formAction(Request $request, Cloison $cloison=null){
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        if(!$cloison){
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Création d\'un type de cloison'
            );
            $titreOnglet = "Ajout type cloison";
            $cloison = new Cloison();
        }else{
            $page = array(
                'title' => 'Matériaux',
                'sub_title' => 'Modification d\'un type de cloison'
            );
            $titreOnglet = "Modif type cloison";
        }
        $entities= $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:Cloison')
            ->findAll();
        $form = $this->createForm(TypeCloisonType::class, $cloison);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!$cloison->getId()){
                $notif='Type de cloison ajouté';
            }else{
                $notif='Type de cloison modifié';
            }
            $em->persist($cloison);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_cloison_list');
        }

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TypeCloison/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_cloison_deleteone")
     */
    public function deleteOneAction(Request $request, Cloison $cloison)
    {
        $notif = "Type ".$cloison->getNom()." supprimé(e)";
        $em = $this->getDoctrine()->getManager();
        $em->remove($cloison);
        $em->flush();
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_cloison_list',[
            'refererIsAForm'=>$refererIsAForm,
        ]);
    }

    /**
     * @Route("/liste/", name="home_construct_cloison_list")
     */
    public function listAction(Request $request)
    {
        $page = array(
            'title' => 'Administration',
            'sub_title' => 'Types de cloison'
        );

        $titreOnglet = "Types Cloisons";

        $entities = $this
            ->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:Cloison')
            ->findAll();

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TypeCloison/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'titreOnglet' => $titreOnglet
        ));
    }

}