<?php
/**
 * Created by PhpStorm.
 * User: kweidner
 * Date: 08/03/2019
 * Time: 21:48
 */

namespace HomeConstruct\BuildBundle\Controller\GrosOeuvre;

use HomeConstruct\BuildBundle\Entity\GrosOeuvre;
use HomeConstruct\BuildBundle\Entity\Piece;
use HomeConstruct\BuildBundle\Form\GrosOeuvreType;
use HomeConstruct\BuildBundle\Form\ProjetType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use HomeConstruct\BuildBundle\Entity\Projet;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * GrosOeuvre controller.
 *
 * @Route("/gros-oeuvre")
 */
class GrosOeuvreController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/{id}", name="home_construct_gros_oeuvre_creation")
     */
    public function addAction(Request $request, Projet $projet)
    {
        if(!$this->getUser()->hasGroup('SUPER ADMIN')) {
            // si le projet n'appartient à l'user courant alors il a pas le droit de le voir
            // on le redirige vers une page d'erreur
            if (!in_array($this->getUser(), $projet->getUsers()->toArray())) {
                $page = array(
                    'title' => 'Création gros oeuvre',
                    'sub_title' => 'Erreur d\'accès'
                );
                return $this->render('@HomeConstructBuild/Error/error_access.html.twig', [
                    'page' => $page
                ]);
            }
        }

        $titreOnglet = "Création Gros Oeuvre";
        $page = array(
            'title' => 'Gros Oeuvre',
            'sub_title' => 'Création du gros oeuvre'
        );
        //si le projet a deja un gros oeuvre on le redirige vers la page d'ou l'on vient
        if($projet->getGrosOeuvre()){
            return $this->redirect($request->headers->get('referer'));
        }
        $grosOeuvre = new GrosOeuvre($this->getDoctrine()->getManager());
        $form = $this->createForm(GrosOeuvreType::class, $grosOeuvre);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $projet->setGrosOeuvre($grosOeuvre);
            $informationBase=$grosOeuvre->getInformationBase();
            $nbPieces=$informationBase->getNbPieces();
            // on crée le nb de pieces renseigné
            for ($i=0;$i<$nbPieces;$i++){
                $piece = new Piece();
                $piece->setGrosOeuvre($grosOeuvre);
                $em->persist($piece);
            }
            $grosOeuvre->setProjet($projet);
            $informationBase->setGrosOeuvre($grosOeuvre);
            $em->persist($grosOeuvre);
            $em->persist($informationBase);
            $em->flush();
            return $this->redirectToRoute('home_construct_gros_oeuvre_profile',[
                'id'=>$grosOeuvre->getId()
            ]);
        };

        return $this->render('@HomeConstructBuild/GrosOeuvre/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'projet' => $projet
        ]);
    }


    /**
     * @Route("/suppression/{id}", name="home_construct_gros_oeuvre_delete")
     */
    public function deleteAction(Request $request, GrosOeuvre $grosOeuvre)
    {
        if(!$this->getUser()->hasGroup('SUPER ADMIN')) {
            // si le projet n'appartient à l'user courant alors il a pas le droit de le voir
            // on le redirige vers une page d'erreur
            if (!in_array($this->getUser(), $grosOeuvre->getProjet()->getUsers()->toArray())) {
                $page = array(
                    'title' => 'Suppression d\'un gros oeuvre',
                    'sub_title' => 'Erreur d\'accès'
                );
                return $this->render('@HomeConstructBuild/Error/error_access.html.twig', [
                    'page' => $page
                ]);
            }
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($grosOeuvre);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', "Gros oeuvre supprimé");
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_projet_profile',['id'=>$grosOeuvre->getProjet()->getId()]);
    }

    /**
     * @Route("/liste", name="home_construct_gros_oeuvre_list")
     */
    public function listAction(Request $request)
    {
        $page = array(
            'title' => 'Gros Oeuvre',
            'sub_title' => 'Tous les gros oeuvres'
        );

        $titreOnglet = "Gros Oeuvre";

        $object = array(
            'name' => 'Gros Oeuvre'
        );
        if(!$this->getUser()->hasGroup('SUPER ADMIN')){
            $entities = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('HomeConstructBuildBundle:GrosOeuvre')
                ->getGrosOeuvresByUser($this->getUser());
        }else{
            $entities = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('HomeConstructBuildBundle:GrosOeuvre')
                ->findAll();
        }

        // l'utilisateur est redirigé vers la vue d'affichage des listes
        return $this->render('@HomeConstructBuild/GrosOeuvre/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'object' => $object,
            'titreOnglet' => $titreOnglet
        ));
    }

    /**
     * @Route("/profile/view/{id}", name="home_construct_gros_oeuvre_profile")
     */
    public function profileAction(Request $request, GrosOeuvre $grosOeuvre)
    {
        if(!$this->getUser()->hasGroup('SUPER ADMIN')) {
            // si le projet n'appartient à l'user courant alors il a pas le droit de le voir
            // on le redirige vers une page d'erreur
            if (!in_array($this->getUser(), $grosOeuvre->getProjet()->getUsers()->toArray())) {
                $page = array(
                    'title' => 'Infos sur un gros oeuvre',
                    'sub_title' => 'Erreur d\'accès'
                );
                return $this->render('@HomeConstructBuild/Error/error_access.html.twig', [
                    'page' => $page
                ]);
            }
        }

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $projet=$grosOeuvre->getProjet();

        // on recupere le service PathHelper pour utiliser des methodes
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);

        // si on vient de supprimer une entité
        if($request->getSession()->get('entity-deleted')){
            $prixGOAvantCalcul=$grosOeuvre->getPrix();
            // on calcule le prix de l'entité dont le prix total est lié à un ou des attribut(s) de l'entité (soit soubassement soit etude sol) que l'on vient de supprimer
            $pathHelper->calculPrixForLinkedEntitiesGo($request->getSession()->get('entity-deleted'),$grosOeuvre->getProjet()->getId());
            $request->getSession()->remove('entity-deleted');
            // on calcule le prix actuel du gros oeuvre puis du projet au cas où l'on vient de supprimer une entité
            $grosOeuvre->calculPrix();
            $em=$this->getDoctrine()->getManager();
            $em->persist($grosOeuvre);
            $prixGOApresCalcul=$grosOeuvre->getPrix();
            $projet->calculPrix();
            $em->flush();
            $pathHelper->showNotifPriceGrosOeuvre($prixGOAvantCalcul,$prixGOApresCalcul);
        }elseif($request->getSession()->get('entity-just-deleted')){
            $request->getSession()->remove('entity-just-deleted');
            $prixAvantCalculGo=$grosOeuvre->getPrix();
            // on calcule le prix actuel du gros oeuvre puis du projet au cas où l'on vient de supprimer une entité
            $pathHelper->calculPrixGoAndProjet($grosOeuvre);
            $prixApresCalculGo=$grosOeuvre->getPrix();
            $pathHelper->showNotifPriceGrosOeuvre($prixAvantCalculGo,$prixApresCalculGo);
        }

        $page = array(
            'title' => 'Le Gros Oeuvre'
        );
        $titreOnglet = "Gros Oeuvre";

        $object = 'Gros Oeuvre';

        return $this->render('@HomeConstructBuild/GrosOeuvre/profile.html.twig', array(
            'page' => $page,
            'object' => $object,
            'projet' =>$projet,
            'grosOeuvre' => $grosOeuvre,
            'idGrosOeuvre' => $grosOeuvre->getId(),
            'titreOnglet' => $titreOnglet
        ));


    }
}
