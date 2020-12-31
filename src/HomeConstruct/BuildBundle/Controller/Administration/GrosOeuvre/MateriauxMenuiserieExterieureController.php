<?php

namespace HomeConstruct\BuildBundle\Controller\Administration\GrosOeuvre;

use HomeConstruct\BuildBundle\Entity\MateriauxMenuiserieExterieure;
use HomeConstruct\BuildBundle\Form\MateriauxMenuiserieExterieureType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Administration Menuiserie Exterieure controller.
 *
 * @Route("/administration/materiaux-menuiserie-exterieure")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class MateriauxMenuiserieExterieureController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/", name="home_construct_materiaux_menuiserie_exterieure_creation")
     * @Route("/edit/{idMateriauxMenuiserieExterieure}", name="home_construct_materiaux_menuiserie_exterieure_edit")
     * @ParamConverter("materiauxMenuiserieExterieure", options={"mapping" : {"idMateriauxMenuiserieExterieure" : "id"}})
     */
    public function formAction(Request $request, MateriauxMenuiserieExterieure $materiauxMenuiserieExterieure=null){
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        if(!$materiauxMenuiserieExterieure){
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Création d\'un matériau des menuiseries extérieures'
            );
            $titreOnglet = "Ajout Matériau Menuiserie Extérieure";
            $materiauxMenuiserieExterieure = new MateriauxMenuiserieExterieure();
        }else{
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Modification d\'un matériau des menuiseries extérieures'
            );
            $titreOnglet = "Modif Matériau Menuiserie Extérieure";
        }
        $entities= $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:MateriauxMenuiserieExterieure')
            ->findAll();
        $form = $this->createForm(MateriauxMenuiserieExterieureType::class, $materiauxMenuiserieExterieure);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!$materiauxMenuiserieExterieure->getId()){
                $notif='Matériau "'.$materiauxMenuiserieExterieure->getNom().'" ajouté';
            }else{
                $notif='Matériau des menuiseries extérieures modifiée';
            }
            $em->persist($materiauxMenuiserieExterieure);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_materiaux_menuiserie_exterieure_list');
        }

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/MateriauxMenuiserieExterieure/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_materiaux_menuiserie_exterieure_deleteone")
     */
    public function deleteOneAction(Request $request, MateriauxMenuiserieExterieure $materiauxMenuiserieExterieure)
    {
        $notif = 'Matériau "'.$materiauxMenuiserieExterieure->getNom().'" supprimé(e)';
        $em = $this->getDoctrine()->getManager();
        $em->remove($materiauxMenuiserieExterieure);
        $em->flush();
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_materiaux_menuiserie_exterieure_list');
    }

    /**
     * @Route("/liste/", name="home_construct_materiaux_menuiserie_exterieure_list")
     */
    public function listAction(Request $request)
    {
        $page = array(
            'title' => 'Administration',
            'sub_title' => 'Matériaux de menuiserie extérieure'
        );

        $titreOnglet = "Materiaux Menuiserie Exterieure";

        $object = 'MateriauxMenuiserieExterieure';

        $entities = $this
            ->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:MateriauxMenuiserieExterieure')
            ->findAll();

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/MateriauxMenuiserieExterieure/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'object' => $object,
            'titreOnglet' => $titreOnglet
        ));
    }

}