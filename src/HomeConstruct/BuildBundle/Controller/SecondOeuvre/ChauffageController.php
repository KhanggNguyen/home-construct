<?php

namespace HomeConstruct\BuildBundle\Controller\SecondOeuvre;

use HomeConstruct\BuildBundle\Entity\SecondOeuvre;
use HomeConstruct\BuildBundle\Entity\Chauffage;
use HomeConstruct\BuildBundle\Form\ChauffageType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use HomeConstruct\BuildBundle\Entity\Projet;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Chauffage controller.
 *
 * @Route("/second-oeuvre/chauffage")
 */
class ChauffageController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/edit/{idChauffage}", name="home_construct_chauffage_edit")
     * @ParamConverter("chauffage", options={"mapping": {"idChauffage": "id"}})
     * @Route("/creation/{idSecondOeuvre}", name="home_construct_chauffage_creation")
     * @ParamConverter("secondOeuvre", options={"mapping": {"idSecondOeuvre": "id"}})
     */
    public function formAction(Request $request, SecondOeuvre $secondOeuvre=null, Chauffage $chauffage=null)
    {
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        if(!$chauffage){
            $chauffage = new Chauffage($this->getDoctrine()->getManager());
            $page = array(
                'title' => 'Second Oeuvre',
                'sub_title' => 'Ajout de chauffage(s)'
            );
            $titreOnglet = "Ajout des Chauffages";
        }else{
            $secondOeuvre = $chauffage->getSecondOeuvre();
            $page = array(
                'title' => 'Second Oeuvre',
                'sub_title' => 'Modification de chauffage(s)'
            );
            $titreOnglet = "Modification des Chauffages";
            $chauffage->setEm($this->getDoctrine()->getManager());
        }
        $entities= $secondOeuvre->getChauffage();
        $form = $this->createForm(ChauffageType::class, $chauffage);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid() && $form->isSubmitted()) {
            $prixAvantCalculSO=$secondOeuvre->getPrix();
            $em = $this->getDoctrine()->getManager();
            $chauffageExiste = $em->getRepository('HomeConstructBuildBundle:Chauffage')
                ->findChauffageExiste($chauffage->getType(), $secondOeuvre);
            if(!$chauffage->getId()){
                if($chauffageExiste){
                    $chauffage->setSecondOeuvre($secondOeuvre);
                    $secondOeuvre->addChauffage($chauffage);
                    $chauffage->setQuantite(($chauffageExiste->getQuantite() + $chauffage->getQuantite()));
                    $chauffage->setEm($em);
                    $em->remove($chauffageExiste);
                    $em->persist($chauffage);
                    $em->flush();
                }else{
                    $chauffage->setSecondOeuvre($secondOeuvre);
                    $secondOeuvre->addChauffage($chauffage);
                    $em->persist($chauffage);
                    $em->flush();
                }
                $notif = "Chauffage ".$chauffage->getType()->getNom()." ajouté(e)";
            }else{
                $em->persist($chauffage);
                $em->flush();
                $notif = "Chauffage modifiée";
            }
            $prixApresCalculSO=$secondOeuvre->getPrix();
            $pathHelper->showNotifPriceSecondOeuvre($prixAvantCalculSO,$prixApresCalculSO);
            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_chauffage_creation',[
                'idSecondOeuvre'=>$secondOeuvre->getId()
            ]);
        }
        return $this->render('@HomeConstructBuild/Chauffage/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'secondOeuvre' => $secondOeuvre,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }


    /**
     * @Route("/suppression/{id}", name="home_construct_chauffage_deleteone")
     */
    public function deleteOneAction(Request $request, Chauffage $chauffage)
    {
        $notif = $chauffage->getType()->getNom()." supprimé(e)";
        $secondOeuvre=$chauffage->getSecondOeuvre();
        $prixAvantCalculSo=$secondOeuvre->getPrix();
        $em = $this->getDoctrine()->getManager();
        $em->remove($chauffage);
        $em->flush();
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $pathHelper->calculPrixSoAndProjet($secondOeuvre);
        $prixApresCalculSo=$secondOeuvre->getPrix();
        $pathHelper->showNotifPriceSecondOeuvre($prixAvantCalculSo,$prixApresCalculSo);
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_chauffage_list', [
            'idSecondOeuvre'=>$secondOeuvre->getId()
        ]);
    }

    /**
     * @Route("/suppression-chauffages/{idSecondOeuvre}", name="home_construct_chauffage_deleteall")
     * @ParamConverter("secondOeuvre", options={"mapping": {"idSecondOeuvre": "id"}})
     */
    public function deleteAllAction(Request $request, SecondOeuvre $secondOeuvre)
    {
        $prixAvantCalculSo=$secondOeuvre->getPrix();
        $em = $this->getDoctrine()->getManager();
        $notif = "Informations sur les chauffages supprimées";
        $chauffages=$secondOeuvre->getChauffage();
        foreach ($chauffages as $chauffage){
            $em->remove($chauffage);
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
     * @Route("/liste/{idSecondOeuvre}", name="home_construct_chauffage_list")
     * @ParamConverter("secondOeuvre", options={"mapping": {"idSecondOeuvre": "id"}})
     */
    public function listAction(Request $request, SecondOeuvre $secondOeuvre)
    {
        $pathHelper=new PathHelper();
        $refererIsProfilePiece=$pathHelper->pathIsProfilePiece($request->headers->get('referer'));
        $page = array(
            'title' => 'Second Oeuvre',
            'sub_title' => 'Vos chauffages'
        );

        $titreOnglet = "Chauffage";

        $object = 'Chauffage';

        $entities = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('HomeConstructBuildBundle:Chauffage')
            ->findBy(['secondOeuvre'=>$secondOeuvre]);

        // l'utilisateur est redirigé vers la vue d'affichage des listes
        return $this->render('@HomeConstructBuild/Chauffage/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'secondOeuvre' => $secondOeuvre,
            'object' => $object,
            'titreOnglet' => $titreOnglet,
            'refererIsProfilePiece'=>$refererIsProfilePiece
        ));
    }
}
