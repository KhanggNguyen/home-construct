<?php

namespace HomeConstruct\BuildBundle\Controller\Administration\GrosOeuvre;

use HomeConstruct\BuildBundle\Entity\MaterielExcavation;
use HomeConstruct\BuildBundle\Form\MaterielExcavationType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * MaterielExcavation controller.
 *
 * @Route("/administration/materiel-excavation")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class MaterielExcavationController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/", name="home_construct_materiel_excavation_creation")
     * @Route("/edit/{idMaterielExcavation}", name="home_construct_materiel_excavation_edit")
     * @ParamConverter("materielExcavation", options={"mapping" : {"idMaterielExcavation" : "id"}})
     */
    public function formAction(Request $request, MaterielExcavation $materielExcavation=null){
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        if(!$materielExcavation){
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Création d\'un matériel pour l\'excavation'
            );
            $titreOnglet = "Ajout Matériel Excavation";
            $materielExcavation = new MaterielExcavation();
        }else{
            $page = array(
                'title' => 'Administration',
                'sub_title' => 'Modification d\'un matériel pour l\'excavation'
            );
            $titreOnglet = "Modif Matériel Excavation";
        }
        $entities= $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:MaterielExcavation')
            ->findAll();
        $form = $this->createForm(MaterielExcavationType::class, $materielExcavation);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!$materielExcavation->getId()){
                $notif='Matériel "'.$materielExcavation->getNom().'" ajouté';
            }else{
                $notif='Matériel pour l\'excavation modifié';
            }
            $em->persist($materielExcavation);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_materiel_excavation_list');
        }

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/MaterielExcavation/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_materiel_excavation_deleteone")
     */
    public function deleteOneAction(Request $request, MaterielExcavation $materielExcavation)
    {
        $notif = 'Matériel "'.$materielExcavation->getNom().'" supprimé(e)';
        $em = $this->getDoctrine()->getManager();
        $em->remove($materielExcavation);
        $em->flush();
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_materiel_excavation_list',[
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     * @Route("/liste/", name="home_construct_materiel_excavation_list")
     */
    public function listAction(Request $request)
    {
        $page = array(
            'title' => 'Administration',
            'sub_title' => 'Matériels pour l\'excavation'
        );

        $titreOnglet = "Materiel Excavation";

        $object = 'MaterielExcavation';

        $entities = $this
            ->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:MaterielExcavation')
            ->findAll();

        return $this->render('@HomeConstructBuild/Administration/GrosOeuvre/MaterielExcavation/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'object' => $object,
            'titreOnglet' => $titreOnglet
        ));
    }

}