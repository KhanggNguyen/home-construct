<?php

namespace HomeConstruct\BuildBundle\Controller\SecondOeuvre;

use HomeConstruct\BuildBundle\Entity\SecondOeuvre;
use HomeConstruct\BuildBundle\Entity\Escalier;
use HomeConstruct\BuildBundle\Form\EscalierType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use HomeConstruct\BuildBundle\Entity\Projet;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Escalier controller.
 *
 * @Route("/second-oeuvre/escalier")
 */
class EscalierController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/edit/{idEscalier}", name="home_construct_escalier_edit")
     * @ParamConverter("escalier", options={"mapping": {"idEscalier": "id"}})
     * @Route("/creation/{idSecondOeuvre}", name="home_construct_escalier_creation")
     * @ParamConverter("secondOeuvre", options={"mapping": {"idSecondOeuvre": "id"}})
     */
    public function formAction(Request $request, SecondOeuvre $secondOeuvre=null, Escalier $escalier=null)
    {
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        if(!$escalier){
            $escalier = new Escalier($this->getDoctrine()->getManager());
            $page = array(
                'title' => 'Second Oeuvre',
                'sub_title' => 'Ajout d\'escalier(s)'
            );
            $titreOnglet = "Ajout Escalier";
        }else{
            $secondOeuvre = $escalier->getSecondOeuvre();
            $page = array(
                'title' => 'Second Oeuvre',
                'sub_title' => 'Modification d\'escalier(s)'
            );
            $escalier->setEm($this->getDoctrine()->getManager());
            $titreOnglet = "Modification Escalier";
        }
        $entities= $secondOeuvre->getEscaliers();

        $form = $this->createForm(EscalierType::class, $escalier);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid() && $form->isSubmitted()) {
            $prixAvantCalculSO=$secondOeuvre->getPrix();
            $em = $this->getDoctrine()->getManager();
            $escalierExiste = $em->getRepository('HomeConstructBuildBundle:Escalier')
                ->findOneBy(['type' => $escalier->getType()
                    , 'materiaux' => $escalier->getMateriaux()
                    , 'secondOeuvre' => $secondOeuvre]);
            if(!$escalier->getId()){
                if($escalierExiste){
                    $escalier->setSecondOeuvre($secondOeuvre);
                    $secondOeuvre->addEscalier($escalier);
                    $escalier->setQuantite(($escalierExiste->getQuantite() + $escalier->getQuantite()));
                    $em->remove($escalierExiste);
                    $em->persist($escalier);
                    $em->flush();
                }else{
                    $escalier->setSecondOeuvre($secondOeuvre);
                    $secondOeuvre->addEscalier($escalier);
                    $em->persist($escalier);
                    $em->flush();
                }
                $notif = $escalier->getQuantite()." ".$escalier->getType()->getNom()." ajouté(e)(s)";
            }else{
                $em->persist($escalier);
                $em->flush();
                $notif = "Escalier modifiée";
            }
            $prixApresCalculSO=$secondOeuvre->getPrix();
            $pathHelper->showNotifPriceSecondOeuvre($prixAvantCalculSO,$prixApresCalculSO);
            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_escalier_creation',[
                'idSecondOeuvre'=>$secondOeuvre->getId()
            ]);
        }
        return $this->render('@HomeConstructBuild/Escalier/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'secondOeuvre' => $secondOeuvre,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }


    /**
     * @Route("/suppression/{id}", name="home_construct_escalier_deleteone")
     */
    public function deleteOneAction(Request $request, Escalier $escalier)
    {
        $notif = $escalier->getType()->getNom()." supprimé(e)";
        $secondOeuvre=$escalier->getSecondOeuvre();
        $prixAvantCalculSo=$secondOeuvre->getPrix();
        $em = $this->getDoctrine()->getManager();
        $em->remove($escalier);
        $em->flush();
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $pathHelper->calculPrixSoAndProjet($secondOeuvre);
        $prixApresCalculSo=$secondOeuvre->getPrix();
        $pathHelper->showNotifPriceSecondOeuvre($prixAvantCalculSo,$prixApresCalculSo);
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_escalier_list', [
            'idSecondOeuvre'=>$secondOeuvre->getId()
        ]);
    }

    /**
     * @Route("/suppression-escaliers/{idSecondOeuvre}", name="home_construct_escalier_deleteall")
     * @ParamConverter("secondOeuvre", options={"mapping": {"idSecondOeuvre": "id"}})
     */
    public function deleteAllAction(Request $request, SecondOeuvre $secondOeuvre)
    {
        $prixAvantCalculSo=$secondOeuvre->getPrix();
        $em = $this->getDoctrine()->getManager();
        $notif = "Informations sur les escaliers supprimées";
        $escaliers=$secondOeuvre->getEscaliers();
        foreach ($escaliers as $escalier){
            $em->remove($escalier);
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
     * @Route("/liste/{idSecondOeuvre}", name="home_construct_escalier_list")
     * @ParamConverter("secondOeuvre", options={"mapping": {"idSecondOeuvre": "id"}})
     */
    public function listAction(Request $request, SecondOeuvre $secondOeuvre)
    {
        $page = array(
            'title' => 'Second Oeuvre',
            'sub_title' => 'Vos escaliers'
        );

        $titreOnglet = "Escalier";

        $object = 'Escaliers';

        $entities = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('HomeConstructBuildBundle:Escalier')
            ->findBy(['secondOeuvre'=>$secondOeuvre]);

        // l'utilisateur est redirigé vers la vue d'affichage des listes
        return $this->render('@HomeConstructBuild/Escalier/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'secondOeuvre' => $secondOeuvre,
            'object' => $object,
            'titreOnglet' => $titreOnglet
        ));
    }
}
