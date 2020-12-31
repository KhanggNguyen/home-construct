<?php

namespace HomeConstruct\BuildBundle\Controller\SecondOeuvre;

use HomeConstruct\BuildBundle\Entity\SecondOeuvre;
use HomeConstruct\BuildBundle\Entity\MenuiserieInterieure;
use HomeConstruct\BuildBundle\Form\MenuiserieInterieureType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use HomeConstruct\BuildBundle\Entity\Projet;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * MenuiserieInterieure controller.
 *
 * @Route("/second-oeuvre/menuiserie-interieure")
 */
class MenuiserieInterieureController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/edit/{idMenuiserieInterieure}", name="home_construct_menuiserie_interieure_edit")
     * @ParamConverter("menuiserieInterieure", options={"mapping": {"idMenuiserieInterieure": "id"}})
     * @Route("/creation/{idSecondOeuvre}", name="home_construct_menuiserie_interieure_creation")
     * @ParamConverter("secondOeuvre", options={"mapping": {"idSecondOeuvre": "id"}})
     */
    public function formAction(Request $request, SecondOeuvre $secondOeuvre=null, MenuiserieInterieure $menuiserieInterieure=null)
    {
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        if(!$menuiserieInterieure){
            $menuiserieInterieure = new MenuiserieInterieure($this->getDoctrine()->getManager());
            $page = array(
                'title' => 'Second Oeuvre',
                'sub_title' => 'Ajout de menuiserie(s) intérieure(s)'
            );
            $titreOnglet = "Ajout Menuiserie Ext";
        }else{
            $secondOeuvre = $menuiserieInterieure->getSecondOeuvre();
            $page = array(
                'title' => 'Second Oeuvre',
                'sub_title' => 'Modification de menuiserie(s) intérieure(s)'
            );
            $titreOnglet = "Modification Menuiserie Int";
            $menuiserieInterieure->setEm($this->getDoctrine()->getManager());
        }
        $entities= $secondOeuvre->getMenuiseriesInterieures();

        $form = $this->createForm(MenuiserieInterieureType::class, $menuiserieInterieure);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid() && $form->isSubmitted()) {
            $prixAvantCalculSO=$secondOeuvre->getPrix();
            $em = $this->getDoctrine()->getManager();
            $menuiserieInterieureExiste = $em->getRepository('HomeConstructBuildBundle:MenuiserieInterieure')
            ->findMenuiserieExiste($menuiserieInterieure->getType(), $menuiserieInterieure->getMateriaux(), $menuiserieInterieure->getDimension()->getLargeur(), $menuiserieInterieure->getDimension()->getLongueur(), $secondOeuvre);
            if(!$menuiserieInterieure->getId()){
                if($menuiserieInterieureExiste){
                    $menuiserieInterieure->setSecondOeuvre($secondOeuvre);
                    $secondOeuvre->addMenuiseriesInterieure($menuiserieInterieure);
                    $menuiserieInterieure->setQuantite(($menuiserieInterieureExiste->getQuantite() + $menuiserieInterieure->getQuantite()));
                    $em->remove($menuiserieInterieureExiste);
                    $em->persist($menuiserieInterieure);
                    $em->flush();
                }else{
                    $menuiserieInterieure->setSecondOeuvre($secondOeuvre);
                    $secondOeuvre->addMenuiseriesInterieure($menuiserieInterieure);
                    $em->persist($menuiserieInterieure);
                    $em->flush();
                }
                $notif = $menuiserieInterieure->getQuantite()." ".$menuiserieInterieure->getType()->getNom()." ajouté(e)(s)";
            }else{
                $em->persist($menuiserieInterieure);
                $em->flush();
                $notif = "Menuiserie intérieure modifiée";
            }
            $prixApresCalculSO=$secondOeuvre->getPrix();
            $pathHelper->showNotifPriceSecondOeuvre($prixAvantCalculSO,$prixApresCalculSO);
            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_menuiserie_interieure_creation',[
                'idSecondOeuvre'=>$secondOeuvre->getId()
            ]);
        }
        return $this->render('@HomeConstructBuild/MenuiserieInterieure/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'secondOeuvre' => $secondOeuvre,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }


    /**
     * @Route("/suppression/{id}", name="home_construct_menuiserie_interieure_deleteone")
     */
    public function deleteOneAction(Request $request, MenuiserieInterieure $menuiserieInterieure)
    {
        $notif = $menuiserieInterieure->getQuantite()." ".$menuiserieInterieure->getType()->getNom()." supprimé(e)(s)";
        $secondOeuvre=$menuiserieInterieure->getSecondOeuvre();
        $prixAvantCalculSo=$secondOeuvre->getPrix();
        $em = $this->getDoctrine()->getManager();
        $em->remove($menuiserieInterieure);
        $em->flush();
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $pathHelper->calculPrixSoAndProjet($secondOeuvre);
        $prixApresCalculSo=$secondOeuvre->getPrix();
        $pathHelper->showNotifPriceSecondOeuvre($prixAvantCalculSo,$prixApresCalculSo);
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_menuiserie_interieure_list', [
            'idSecondOeuvre'=>$secondOeuvre->getId()
        ]);
    }

    /**
     * @Route("/suppression-menuiseries-interieures/{idSecondOeuvre}", name="home_construct_menuiserie_interieure_deleteall")
     * @ParamConverter("secondOeuvre", options={"mapping": {"idSecondOeuvre": "id"}})
     */
    public function deleteAllAction(Request $request, SecondOeuvre $secondOeuvre)
    {
        $prixAvantCalculSo=$secondOeuvre->getPrix();
        $em = $this->getDoctrine()->getManager();
        $notif = "Informations sur les menuiseries extérieures supprimées";
        $menuiseriesInterieures=$secondOeuvre->getMenuiseriesInterieures();
        foreach ($menuiseriesInterieures as $menuiserie){
            $em->remove($menuiserie);
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
     * @Route("/liste/{idSecondOeuvre}", name="home_construct_menuiserie_interieure_list")
     * @ParamConverter("secondOeuvre", options={"mapping": {"idSecondOeuvre": "id"}})
     */
    public function listAction(Request $request, SecondOeuvre $secondOeuvre)
    {
        $page = array(
            'title' => 'Second Oeuvre',
            'sub_title' => 'Vos menuiseries intérieures'
        );

        $titreOnglet = "Menuiserie intérieure";

        $object = 'Menuiserie Interieure';

        $entities = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('HomeConstructBuildBundle:MenuiserieInterieure')
            ->findBy(['secondOeuvre'=>$secondOeuvre]);

        // l'utilisateur est redirigé vers la vue d'affichage des listes
        return $this->render('@HomeConstructBuild/MenuiserieInterieure/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'secondOeuvre' => $secondOeuvre,
            'object' => $object,
            'titreOnglet' => $titreOnglet
        ));
    }
}
