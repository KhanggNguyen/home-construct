<?php

namespace HomeConstruct\BuildBundle\Controller\SecondOeuvre;

use HomeConstruct\BuildBundle\Entity\SecondOeuvre;
use HomeConstruct\BuildBundle\Entity\RevetementSol;
use HomeConstruct\BuildBundle\Form\RevetementSolType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use HomeConstruct\BuildBundle\Entity\Projet;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * RevetementSol controller.
 *
 * @Route("/second-oeuvre/revetement-sol")
 */
class RevetementSolController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/edit/{idRevetementSol}", name="home_construct_revetement_sol_edit")
     * @ParamConverter("revetementSol", options={"mapping": {"idRevetementSol": "id"}})
     * @Route("/creation/{idSecondOeuvre}", name="home_construct_revetement_sol_creation")
     * @ParamConverter("secondOeuvre", options={"mapping": {"idSecondOeuvre": "id"}})
     */
    public function formAction(Request $request, SecondOeuvre $secondOeuvre=null, RevetementSol $revetementSol=null)
    {
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));
        if(!$revetementSol){
            $revetementSol = new RevetementSol($this->getDoctrine()->getManager());
            $page = array(
                'title' => 'Second Oeuvre',
                'sub_title' => 'Ajout de revêtement(s) de sol'
            );
            $titreOnglet = "Ajout des Revêtement Sol";
        }else{
            $secondOeuvre = $revetementSol->getSecondOeuvre();
            $page = array(
                'title' => 'Second Oeuvre',
                'sub_title' => 'Modification de revêtement(s) de sol'
            );
            $titreOnglet = "Modification des Revetement Sol";
            $revetementSol->setEm($this->getDoctrine()->getManager());
        }
        $entities= $secondOeuvre->getRevetementSol();

        $form = $this->createForm(RevetementSolType::class, $revetementSol);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid() && $form->isSubmitted()) {
            $prixAvantCalculSO=$secondOeuvre->getPrix();
            $em = $this->getDoctrine()->getManager();
            $revetementSolExiste = $em->getRepository('HomeConstructBuildBundle:RevetementSol')
                ->findRevetementSolExiste($revetementSol->getType(), $secondOeuvre);
            if(!$revetementSol->getId()){
                if($revetementSolExiste){
                    $revetementSol->setSecondOeuvre($secondOeuvre);
                    $secondOeuvre->addRevetementSol($revetementSol);
                    $revetementSol->setQuantite(($revetementSolExiste->getQuantite() + $revetementSol->getQuantite()));
                    $revetementSol->setEm($em);
                    $em->remove($revetementSolExiste);
                    $em->persist($revetementSol);
                    $em->flush();
                }else{
                    $revetementSol->setSecondOeuvre($secondOeuvre);
                    $secondOeuvre->addRevetementSol($revetementSol);
                    $em->persist($revetementSol);
                    $em->flush();
                }
                $notif = $revetementSol->getQuantiteM2()."m² de sol en ".$revetementSol->getType()->getNom()." ajouté(s)";
            }else{
                $em->persist($revetementSol);
                $em->flush();
                $notif = "Revêtement de sol modifiée";
            }
            $prixApresCalculSO=$secondOeuvre->getPrix();
            $pathHelper->showNotifPriceSecondOeuvre($prixAvantCalculSO,$prixApresCalculSO);
            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_revetement_sol_creation',[
                'idSecondOeuvre'=>$secondOeuvre->getId()
            ]);
        }
        return $this->render('@HomeConstructBuild/RevetementSol/form.html.twig', [
            'grosOeuvre' => $secondOeuvre->getProjet()->getGrosOeuvre(),
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'secondOeuvre' => $secondOeuvre,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }


    /**
     * @Route("/suppression/{id}", name="home_construct_revetement_sol_deleteone")
     */
    public function deleteOneAction(Request $request, RevetementSol $revetementSol)
    {
        $notif = $revetementSol->getQuantiteM2()."m² de sol en ".$revetementSol->getType()->getNom()." supprimé(e)";
        $secondOeuvre=$revetementSol->getSecondOeuvre();
        $prixAvantCalculSo=$secondOeuvre->getPrix();
        $em = $this->getDoctrine()->getManager();
        $em->remove($revetementSol);
        $em->flush();
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $pathHelper->calculPrixSoAndProjet($secondOeuvre);
        $prixApresCalculSo=$secondOeuvre->getPrix();
        $pathHelper->showNotifPriceSecondOeuvre($prixAvantCalculSo,$prixApresCalculSo);
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_revetement_sol_list', [
            'idSecondOeuvre'=>$secondOeuvre->getId()
        ]);
    }

    /**
     * @Route("/suppression-revetement-sols/{idSecondOeuvre}", name="home_construct_revetement_sol_deleteall")
     * @ParamConverter("secondOeuvre", options={"mapping": {"idSecondOeuvre": "id"}})
     */
    public function deleteAllAction(Request $request, SecondOeuvre $secondOeuvre)
    {
        $prixAvantCalculSo=$secondOeuvre->getPrix();
        $em = $this->getDoctrine()->getManager();
        $notif = "Informations sur les revêtements des sols supprimées";
        $revetementSols=$secondOeuvre->getRevetementSol();
        foreach ($revetementSols as $revetementSol){
            $em->remove($revetementSol);
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
     * @Route("/liste/{idSecondOeuvre}", name="home_construct_revetement_sol_list")
     * @ParamConverter("secondOeuvre", options={"mapping": {"idSecondOeuvre": "id"}})
     */
    public function listAction(Request $request, SecondOeuvre $secondOeuvre)
    {
        $pathHelper=new PathHelper();
        $refererIsProfilePiece=$pathHelper->pathIsProfilePiece($request->headers->get('referer'));
        $page = array(
            'title' => 'Second Oeuvre',
            'sub_title' => 'Vos revêtements de sol'
        );

        $titreOnglet = "Revetement Sol";

        $object = 'Revetement Sol';

        $entities = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('HomeConstructBuildBundle:RevetementSol')
            ->findBy(['secondOeuvre'=>$secondOeuvre]);

        // l'utilisateur est redirigé vers la vue d'affichage des listes
        return $this->render('@HomeConstructBuild/RevetementSol/list.html.twig', array(
            'grosOeuvre' => $secondOeuvre->getProjet()->getGrosOeuvre(),
            'page' => $page,
            'entities' => $entities,
            'secondOeuvre' => $secondOeuvre,
            'object' => $object,
            'titreOnglet' => $titreOnglet,
            'refererIsProfilePiece'=>$refererIsProfilePiece
        ));
    }
}
