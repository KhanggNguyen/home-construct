<?php

namespace HomeConstruct\BuildBundle\Controller\Administration\GrosOeuvre;

use HomeConstruct\BuildBundle\Entity\ReseauGazNaturel;
use HomeConstruct\BuildBundle\Form\ReseauGazNaturelType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * FounrisseurGazNaturel controller.
 *
 * @Route("/administration/fournisseur-gaz-naturel")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class FournisseurGazNaturelController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/", name="home_construct_type_reseau_gaz_naturel_creation")
     * @Route("/edit/{idTypeReseauGazNaturel}", name="home_construct_type_reseau_gaz_naturel_edit")
     * @ParamConverter("typeReseauGazNaturel", options={"mapping" : {"idTypeReseauGazNaturel" : "id"}})
     */
    public function formAction(Request $request, ReseauGazNaturel $typeReseauGazNaturel=null){
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        if(!$typeReseauGazNaturel){
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Ajout d\'un fournisseur de gaz naturel'
            );
            $titreOnglet = "Ajout Fournisseur Gaz Naturel";
            $typeReseauGazNaturel = new ReseauGazNaturel();
        }else{
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Modification d\'un fournisseur de gaz naturel'
            );
            $titreOnglet = "Modif Fournisseur Gaz Naturel";
        }
        $entities= $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:ReseauGazNaturel')
            ->findAll();
        $form = $this->createForm(ReseauGazNaturelType::class, $typeReseauGazNaturel);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!$typeReseauGazNaturel->getId()){
                $notif='Fournisseur "'. $typeReseauGazNaturel->getNom().'" ajouté';
            }else{
                $notif='Fournisseur modifié';
            }
            $em->persist($typeReseauGazNaturel);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_type_reseau_gaz_naturel_list');
        }

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/FournisseurGazNaturel/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_type_reseau_gaz_naturel_deleteone")
     */
    public function deleteOneAction(Request $request, ReseauGazNaturel $typeReseauGazNaturel)
    {
        $notif = 'Fournisseur "'.$typeReseauGazNaturel->getNom().'" supprimé';
        $em = $this->getDoctrine()->getManager();
        $em->remove($typeReseauGazNaturel);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_type_reseau_gaz_naturel_list');
    }

    /**
     * @Route("/liste/", name="home_construct_type_reseau_gaz_naturel_list")
     */
    public function listAction(Request $request)
    {
        $page = array(
            'title' => 'Administration',
            'sub_title' => 'Fournisseurs de gaz naturel'
        );

        $titreOnglet = "Fournisseur Gaz Naturel";

        $entities = $this
            ->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:ReseauGazNaturel')
            ->findAll();

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/FournisseurGazNaturel/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'titreOnglet' => $titreOnglet
        ));
    }

}