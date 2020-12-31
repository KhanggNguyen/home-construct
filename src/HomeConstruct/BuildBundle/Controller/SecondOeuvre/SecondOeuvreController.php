<?php 

namespace HomeConstruct\BuildBundle\Controller\SecondOeuvre;

use HomeConstruct\BuildBundle\Entity\SecondOeuvre;
use HomeConstruct\BuildBundle\Form\SecondOeuvreType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use HomeConstruct\BuildBundle\Entity\Projet;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * SecondOeuvre controller.
 *
 * @Route("/second-oeuvre")
 */
class SecondOeuvreController extends Controller{
	
	/**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/{id}", name="home_construct_second_oeuvre_creation")
     */
    public function addAction(Request $request, Projet $projet)
    {
        if(!$this->getUser()->hasGroup('SUPER ADMIN')) {
            // si le projet n'appartient à l'user courant alors il a pas le droit de le voir
            // on le redirige vers une page d'erreur
            if (!in_array($this->getUser(), $projet->getUsers()->toArray())) {
                $page = array(
                    'title' => 'Création d\'un second oeuvre',
                    'sub_title' => 'Erreur d\'accès'
                );
                return $this->render('@HomeConstructBuild/Error/error_access.html.twig', [
                    'page' => $page
                ]);
            }
        }
    	$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if($projet->getSecondOeuvre()){
            return $this->redirect($request->headers->get('referer'));
        }
        $page = array(
            'title' => 'Le Second Oeuvre',
            'sub_title' => 'Second Oeuvre'
        );
        $titreOnglet = "Second Oeuvre";
        $object = 'Second Oeuvre';

        $notif="Second Oeuvre crée";

        $secondOeuvre = new SecondOeuvre();
        $secondOeuvre->setProjet($projet);
        $projet->setSecondOeuvre($secondOeuvre);
        $em = $this->getDoctrine()->getManager();
        $em->persist($secondOeuvre);
        $em->persist($projet);
        $em->flush();

        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');

        return $this->redirectToRoute('home_construct_second_oeuvre_profile', [
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'projet'=>$projet,
            'id' => $secondOeuvre->getId(),
            'secondOeuvre' => $secondOeuvre,
        ]);
    }

    /**
     * @Route("/profile/view/{id}", name="home_construct_second_oeuvre_profile")
     */
    public function profileAction(Request $request, SecondOeuvre $secondOeuvre)
    {
        if(!$this->getUser()->hasGroup('SUPER ADMIN')) {
            // si le projet n'appartient à l'user courant alors il a pas le droit de le voir
            // on le redirige vers une page d'erreur
            if (!in_array($this->getUser(), $secondOeuvre->getProjet()->getUsers()->toArray())) {
                $page = array(
                    'title' => 'Infos sur un second oeuvre',
                    'sub_title' => 'Erreur d\'accès'
                );
                return $this->render('@HomeConstructBuild/Error/error_access.html.twig', [
                    'page' => $page
                ]);
            }
        }

        $projet = $secondOeuvre->getProjet();
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);

        if($request->getSession()->get('entity-just-deleted')){
            $request->getSession()->remove('entity-just-deleted');
            $prixAvantCalculSo=$secondOeuvre->getPrix();
            // on calcule le prix actuel du gros oeuvre puis du projet au cas où l'on vient de supprimer une entité
            $pathHelper->calculPrixSoAndProjet($secondOeuvre);
            $prixApresCalculSo=$secondOeuvre->getPrix();
            $pathHelper->showNotifPriceSecondOeuvre($prixAvantCalculSo,$prixApresCalculSo);
        }

        $page = array(
            'title' => 'Le Second Oeuvre'
        );
        $titreOnglet = "Second Oeuvre";

        $object = 'Second Oeuvre';
        return $this->render('@HomeConstructBuild/SecondOeuvre/profile.html.twig', array(
            'page' => $page,
            'object' => $object,
            'projet' => $projet,
            'secondOeuvre' => $secondOeuvre,
            'idSecondOeuvre' => $secondOeuvre->getId(),
            'titreOnglet' => $titreOnglet
        ));
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_second_oeuvre_delete")
     */
    public function deleteAction(Request $request, SecondOeuvre $secondOeuvre)
    {
        if(!$this->getUser()->hasGroup('SUPER ADMIN')) {
            // si le projet n'appartient à l'user courant alors il a pas le droit de le voir
            // on le redirige vers une page d'erreur
            if (!in_array($this->getUser(), $secondOeuvre->getProjet()->getUsers()->toArray())) {
                $page = array(
                    'title' => 'Suppression d\'un second oeuvre',
                    'sub_title' => 'Erreur d\'accès'
                );
                return $this->render('@HomeConstructBuild/Error/error_access.html.twig', [
                    'page' => $page
                ]);
            }
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($secondOeuvre);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', "Second Oeuvre supprimé");
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_projet_profile',['id'=>$secondOeuvre->getProjet()->getId()]);
    }
}


