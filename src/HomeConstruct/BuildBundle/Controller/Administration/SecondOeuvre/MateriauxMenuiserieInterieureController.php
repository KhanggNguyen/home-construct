<?php

namespace HomeConstruct\BuildBundle\Controller\Administration\SecondOeuvre;

use HomeConstruct\BuildBundle\Entity\MateriauxMenuiserieInterieure;
use HomeConstruct\BuildBundle\Form\MateriauxMenuiserieInterieureType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Administration Menuiserie Interieure controller.
 *
 * @Route("/administration/materiaux-menuiserie-interieure")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class MateriauxMenuiserieInterieureController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/", name="home_construct_materiaux_menuiserie_interieure_creation")
     * @Route("/edit/{idMateriauxMenuiserieInterieure}", name="home_construct_materiaux_menuiserie_interieure_edit")
     * @ParamConverter("materiauxMenuiserieInterieure", options={"mapping" : {"idMateriauxMenuiserieInterieure" : "id"}})
     */
    public function formAction(Request $request, MateriauxMenuiserieInterieure $materiauxMenuiserieInterieure=null){
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        if(!$materiauxMenuiserieInterieure){
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Création d\'un matériau de menuiserie intérieure'
            );
            $titreOnglet = "Ajout Matériau Menuiserie Interieure";
            $materiauxMenuiserieInterieure = new MateriauxMenuiserieInterieure();
        }else{
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Modification des matériaux menuiseries intérieures'
            );
            $titreOnglet = "Modif d'un matériau de menuiserie intérieure";
        }
        $entities= $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:MateriauxMenuiserieInterieure')
            ->findAll();
        $form = $this->createForm(MateriauxMenuiserieInterieureType::class, $materiauxMenuiserieInterieure);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!$materiauxMenuiserieInterieure->getId()){
                $notif='Matériau "'.$materiauxMenuiserieInterieure->getNom().'" ajouté';
            }else{
                $notif='Matériau de menuiserie intérieure modifié';
            }
            $em->persist($materiauxMenuiserieInterieure);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_materiaux_menuiserie_interieure_list');
        }

        return $this->render('@HomeConstructBuild/Administration/SecondOeuvre/MateriauxMenuiserieInterieure/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_materiaux_menuiserie_interieure_deleteone")
     */
    public function deleteOneAction(Request $request, MateriauxMenuiserieInterieure $materiauxMenuiserieInterieure)
    {
        $notif = 'Matériau "'.$materiauxMenuiserieInterieure->getNom().'"" supprimé(e)';
        $em = $this->getDoctrine()->getManager();
        $em->remove($materiauxMenuiserieInterieure);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_materiaux_menuiserie_interieure_list');
    }

    /**
     * @Route("/liste/", name="home_construct_materiaux_menuiserie_interieure_list")
     */
    public function listAction(Request $request)
    {
        $page = array(
            'title' => 'Administration',
            'sub_title' => 'Matériaux de menuiserie intérieure'
        );

        $titreOnglet = "Matériaux Menuiserie Interieure";

        $object = 'MateriauxMenuiserieInterieure';

        $entities = $this
            ->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:MateriauxMenuiserieInterieure')
            ->findAll();

        return $this->render('@HomeConstructBuild/Administration/SecondOeuvre/MateriauxMenuiserieInterieure/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'object' => $object,
            'titreOnglet' => $titreOnglet
        ));
    }

}