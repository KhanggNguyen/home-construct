<?php

namespace HomeConstruct\BuildBundle\Controller\Administration\GrosOeuvre;

use HomeConstruct\BuildBundle\Entity\TailleArbre;
use HomeConstruct\BuildBundle\Form\TailleArbreType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Taille Arbre controller.
 *
 * @Route("/administration/taille-arbre")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class TailleArbreController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/", name="home_construct_taille_arbre_creation")
     * @Route("/edit/{idTailleArbre}", name="home_construct_taille_arbre_edit")
     * @ParamConverter("tailleArbre", options={"mapping" : {"idTailleArbre" : "id"}})
     */
    public function formAction(Request $request, TailleArbre $tailleArbre=null){
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        if(!$tailleArbre){
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Création d\'une taille d\'arbre'
            );
            $titreOnglet = "Ajout Taille Arbre";
            $tailleArbre = new TailleArbre();
        }else{
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Modification d\'une taille d\'arbre'
            );
            $titreOnglet = "Modif de la taille de l'arbre";
        }
        $entities= $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TailleArbre')
            ->findAll();
        $form = $this->createForm(TailleArbreType::class, $tailleArbre);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!$tailleArbre->getId()){
                $notif='Informations sur la taille de l\'arbre ajoutées';
            }else{
                $notif='Informations sur la taille de l\'arbre modifiées';
            }
            $em->persist($tailleArbre);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_taille_arbre_list');
        }

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TailleArbre/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_taille_arbre_deleteone")
     */
    public function deleteOneAction(Request $request, TailleArbre $tailleArbre)
    {
        $notif = "Type ".$tailleArbre->getNom()." supprimé(e)";
        $em = $this->getDoctrine()->getManager();
        $em->remove($tailleArbre);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_taille_arbre_list');
    }

    /**
     * @Route("/liste/", name="home_construct_taille_arbre_list")
     */
    public function listAction(Request $request)
    {
        $page = array(
            'title' => 'Administration',
            'sub_title' => 'Tailles d\'arbres'
        );

        $titreOnglet = "TailleArbre";

        $object = 'TailleArbre';

        $entities = $this
            ->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TailleArbre')
            ->findAll();

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/TailleArbre/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'object' => $object,
            'titreOnglet' => $titreOnglet
        ));
    }

}