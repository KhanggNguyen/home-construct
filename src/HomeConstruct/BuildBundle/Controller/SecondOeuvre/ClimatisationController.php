<?php

namespace HomeConstruct\BuildBundle\Controller\SecondOeuvre;

use HomeConstruct\BuildBundle\Entity\SecondOeuvre;
use HomeConstruct\BuildBundle\Entity\Climatisation;
use HomeConstruct\BuildBundle\Form\ClimatisationType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use HomeConstruct\BuildBundle\Entity\Projet;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Climatisation controller.
 * @Route("/second-oeuvre/climatisation")
 */
class ClimatisationController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/edit/{idClimatisation}", name="home_construct_climatisation_edit")
     * @ParamConverter("climatisation", options={"mapping": {"idClimatisation": "id"}})
     * @Route("/creation/{idSecondOeuvre}", name="home_construct_climatisation_creation")
     * @ParamConverter("secondOeuvre", options={"mapping": {"idSecondOeuvre": "id"}})
     */
    public function formAction(Request $request, SecondOeuvre $secondOeuvre=null, Climatisation $climatisation=null)
    {
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        if(!$climatisation){
            $climatisation = new Climatisation($this->getDoctrine()->getManager());
            $page = array(
                'title' => 'Second Oeuvre',
                'sub_title' => 'Ajout de climatisation(s)'
            );
            $titreOnglet = "Ajout Climatisation";
        }else{
            $secondOeuvre = $climatisation->getSecondOeuvre();
            $page = array(
                'title' => 'Second Oeuvre',
                'sub_title' => 'Modification de climatisation(s)'
            );
            $titreOnglet = "Modification des Climatisation";
            $climatisation->setEm($this->getDoctrine()->getManager());
        }
        $entities= $secondOeuvre->getClimatisation();

        $form = $this->createForm(ClimatisationType::class, $climatisation);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid() && $form->isSubmitted()) {
            $prixAvantCalculSO=$secondOeuvre->getPrix();
            $em = $this->getDoctrine()->getManager();
            $climatisationExiste = $em->getRepository('HomeConstructBuildBundle:Climatisation')
                ->findClimatisationExiste($climatisation->getType(), $secondOeuvre);
            if(!$climatisation->getId()){
                if($climatisationExiste){
                    $climatisation->setSecondOeuvre($secondOeuvre);
                    $secondOeuvre->addClimatisation($climatisation);
                    $climatisation->setQuantite(($climatisationExiste->getQuantite() + $climatisation->getQuantite()));
                    $climatisation->setEm($em);
                    $em->remove($climatisationExiste);
                    $em->persist($climatisation);
                    $em->flush();
                }else{
                    $climatisation->setSecondOeuvre($secondOeuvre);
                    $secondOeuvre->addClimatisation($climatisation);
                    $em->persist($climatisation);
                    $em->flush();
                }
                $notif = $climatisation->getQuantite()." ".$climatisation->getType()->getNom()." ajouté(e)(s)";
            }else{
                $em->persist($climatisation);
                $em->flush();
                $notif = " Climatisation modifiée";
            }
            $prixApresCalculSO=$secondOeuvre->getPrix();
            $pathHelper->showNotifPriceSecondOeuvre($prixAvantCalculSO,$prixApresCalculSO);
            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_climatisation_creation',[
                'idSecondOeuvre'=>$secondOeuvre->getId()
            ]);
        }
        return $this->render('@HomeConstructBuild/Climatisation/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'secondOeuvre' => $secondOeuvre,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }


    /**
     * @Route("/suppression/{id}", name="home_construct_climatisation_deleteone")
     */
    public function deleteOneAction(Request $request, Climatisation $climatisation)
    {
        $notif = $climatisation->getQuantite()." ".$climatisation->getType()->getNom()." supprimé(e)";
        $secondOeuvre=$climatisation->getSecondOeuvre();
        $prixAvantCalculSo=$secondOeuvre->getPrix();
        $em = $this->getDoctrine()->getManager();
        $em->remove($climatisation);
        $em->flush();
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $pathHelper->calculPrixSoAndProjet($secondOeuvre);
        $prixApresCalculSo=$secondOeuvre->getPrix();
        $pathHelper->showNotifPriceSecondOeuvre($prixAvantCalculSo,$prixApresCalculSo);
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_climatisation_list', [
            'idSecondOeuvre'=>$secondOeuvre->getId()
        ]);
    }

    /**
     * @Route("/suppression-climatisations/{idSecondOeuvre}", name="home_construct_climatisation_deleteall")
     * @ParamConverter("secondOeuvre", options={"mapping": {"idSecondOeuvre": "id"}})
     */
    public function deleteAllAction(Request $request, SecondOeuvre $secondOeuvre)
    {
        $prixAvantCalculSo=$secondOeuvre->getPrix();
        $em = $this->getDoctrine()->getManager();
        $notif = "Informations sur les climatisations supprimées";
        $climatisations=$secondOeuvre->getClimatisation();
        foreach ($climatisations as $climatisation){
            $em->remove($climatisation);
        }
        $em->flush();
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $pathHelper->calculPrixSoAndProjet($secondOeuvre);
        $prixApresCalculSo=$secondOeuvre->getPrix();
        $pathHelper->showNotifPriceSecondOeuvre($prixAvantCalculSo,$prixApresCalculSo);
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_second_oeuvre_profile', [
            'id'=>$secondOeuvre->getId()
        ]);
    }

    /**
     * @Route("/liste/{idSecondOeuvre}", name="home_construct_climatisation_list")
     * @ParamConverter("secondOeuvre", options={"mapping": {"idSecondOeuvre": "id"}})
     */
    public function listAction(Request $request, SecondOeuvre $secondOeuvre)
    {
        $pathHelper=new PathHelper();
        $refererIsProfilePiece=$pathHelper->pathIsProfilePiece($request->headers->get('referer'));
        $page = array(
            'title' => 'Second Oeuvre',
            'sub_title' => 'Vos climatisation'
        );

        $titreOnglet = " Climatisation";

        $object = ' Climatisation';

        $entities = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('HomeConstructBuildBundle:Climatisation')
            ->findBy(['secondOeuvre'=>$secondOeuvre]);

        // l'utilisateur est redirigé vers la vue d'affichage des listes
        return $this->render('@HomeConstructBuild/Climatisation/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'secondOeuvre' => $secondOeuvre,
            'object' => $object,
            'titreOnglet' => $titreOnglet,
            'refererIsProfilePiece'=>$refererIsProfilePiece
        ));
    }
}
