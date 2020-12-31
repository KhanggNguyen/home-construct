<?php
/**
 * Created by PhpStorm.
 * User: kweidner
 * Date: 08/03/2019
 * Time: 21:48
 */

namespace HomeConstruct\BuildBundle\Controller\GrosOeuvre;

use HomeConstruct\BuildBundle\Entity\GrosOeuvre;
use HomeConstruct\BuildBundle\Entity\MenuiserieExterieure;
use HomeConstruct\BuildBundle\Form\MenuiserieExterieureType;
use HomeConstruct\BuildBundle\Form\ProjetType;
use Doctrine\Common\Persistence\ObjectManager;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use HomeConstruct\BuildBundle\Entity\Projet;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * MenuiserieExterieure controller.
 *
 * @Route("/gros-oeuvre/menuiserie-exterieure")
 */
class MenuiserieExterieureController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/edit/{idMenuiserieExterieure}", name="home_construct_menuiserie_exterieure_edit")
     * @ParamConverter("menuiserieExterieure", options={"mapping": {"idMenuiserieExterieure": "id"}})
     * @Route("/creation/{idGrosOeuvre}", name="home_construct_menuiserie_exterieure_creation")
     * @ParamConverter("grosOeuvre", options={"mapping": {"idGrosOeuvre": "id"}})
     */
    public function formAction(Request $request, GrosOeuvre $grosOeuvre=null, MenuiserieExterieure $menuiserieExterieure=null)
    {
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));
        if(!$menuiserieExterieure){
            $menuiserieExterieure = new MenuiserieExterieure($this->getDoctrine()->getManager());
            $page = array(
                'title' => 'Gros Oeuvre',
                'sub_title' => 'Ajout de menuiserie(s) extérieure(s)'
            );
            $titreOnglet = "Ajout Menuiserie Ext";
        }else{
            $grosOeuvre = $menuiserieExterieure->getGrosOeuvre();
            $page = array(
                'title' => 'Gros Oeuvre',
                'sub_title' => 'Modification de menuiserie(s) extérieure(s)'
            );
            $menuiserieExterieure->setEm($this->getDoctrine()->getManager());
            $titreOnglet = "Modification Menuiserie Ext";
        }
        $entities= $grosOeuvre->getMenuiseriesExterieures();
        $form = $this->createForm(MenuiserieExterieureType::class, $menuiserieExterieure);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $prixAvantCalculGO=$grosOeuvre->getPrix();
            //on teste si il existe pas deja la meme menuiserie exterieure avec les memes parametres
            $em = $this->getDoctrine()->getManager();
            $menuiseriesExterieuresGrosOeuvre=$em->getRepository('HomeConstructBuildBundle:MenuiserieExterieure')
                ->findBy(['grosOeuvre'=>$grosOeuvre]);
            if($menuiseriesExterieuresGrosOeuvre != null){
                $existe=false;
                $toRemove=false;
                /* on teste ici s'il existe une menuiserie exterieure deja
                existante dans le gros oeuvre qui a les memes attributs
                que la menuiserie que l'on vient de créer/modifier
                si elle existe alors on modifie seulement la quantité de celle existante
                */
                foreach ($menuiseriesExterieuresGrosOeuvre as $menuiserie){
                    if($menuiserie->getId()!=$menuiserieExterieure->getId()) {
                        if ($menuiserie->getMateriaux() == $menuiserieExterieure->getMateriaux()) {
                            if ($menuiserie->getType() == $menuiserieExterieure->getType()) {
                                if ($menuiserie->getDimension()->getLargeur() == $menuiserieExterieure->getDimension()->getLargeur()) {
                                    if ($menuiserie->getDimension()->getLongueur() == $menuiserieExterieure->getDimension()->getLongueur()) {
                                        $menuiserie->setQuantite($menuiserie->getQuantite() + $menuiserieExterieure->getQuantite());
                                        $existe = true;
                                        if ($menuiserieExterieure->getId()) {
                                            $toRemove = true;
                                        }
                                        $em->persist($menuiserie);
                                        $em->flush();
                                        break; //on sort du foreach
                                    }
                                }
                            }
                        }
                    }
                }
                /* si toRemove est true alors on doit supprimer la menuiserieExterieure
                que l'on vient de modifier car elle existe deja (on ajoutera juste sa quantité
                à celle deja existante)
                */
                if(!$menuiserieExterieure->getId()){
                    $notif = $menuiserieExterieure->getQuantite()." ".$menuiserieExterieure->getType()->getNom()." ajouté(e)(s)";
                }else{
                    $notif = "Menuiserie extérieure modifiée";
                }
                if($toRemove){
                    $em->remove($menuiserieExterieure);
                    $em->flush();
                }
                if(!$existe){
                    $menuiserieExterieure->setGrosOeuvre($grosOeuvre);
                    $em->persist($menuiserieExterieure);
                    $em->flush();
                }
            }else{
                if(!$menuiserieExterieure->getId()){
                    $notif = $menuiserieExterieure->getQuantite()." ".$menuiserieExterieure->getType()->getNom()." ajouté(e)(s)";
                }else{
                    $notif = "Menuiserie extérieure modifiée";
                }
                $menuiserieExterieure->setGrosOeuvre($grosOeuvre);
                $em->persist($menuiserieExterieure);
                $em->flush();
            }
            $prixApresCalculGO=$grosOeuvre->getPrix();
            $pathHelper->showNotifPriceGrosOeuvre($prixAvantCalculGO,$prixApresCalculGO);
            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_menuiserie_exterieure_list',[
                'idGrosOeuvre'=>$grosOeuvre->getId()
            ]);
        };

        return $this->render('@HomeConstructBuild/MenuiserieExterieure/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'grosOeuvre' => $grosOeuvre,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }


    /**
     * @Route("/suppression/{id}", name="home_construct_menuiserie_exterieure_deleteone")
     */
    public function deleteOneAction(Request $request, MenuiserieExterieure $menuiserieExterieure)
    {
        $notif = $menuiserieExterieure->getQuantite()." ".$menuiserieExterieure->getType()->getNom()." supprimé(e)";
        $grosOeuvre=$menuiserieExterieure->getGrosOeuvre();
        $prixAvantCalculGo=$grosOeuvre->getPrix();
        $em = $this->getDoctrine()->getManager();
        $em->remove($menuiserieExterieure);
        $em->flush();
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $pathHelper->calculPrixGoAndProjet($grosOeuvre);
        $prixApresCalculGo=$grosOeuvre->getPrix();
        $pathHelper->showNotifPriceGrosOeuvre($prixAvantCalculGo,$prixApresCalculGo);
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_menuiserie_exterieure_list', [
            'idGrosOeuvre'=>$grosOeuvre->getId()
        ]);
    }

    /**
     * @Route("/suppression-menuiseries-exterieures/{idGrosOeuvre}", name="home_construct_menuiserie_exterieure_deleteall")
     * @ParamConverter("grosOeuvre", options={"mapping": {"idGrosOeuvre": "id"}})
     */
    public function deleteAllAction(Request $request, GrosOeuvre $grosOeuvre)
    {
        $prixAvantCalculGo=$grosOeuvre->getPrix();
        $em = $this->getDoctrine()->getManager();
        $notif = "Informations sur les menuiseries extérieures supprimées";
        $menuiseriesExterieures=$grosOeuvre->getMenuiseriesExterieures();
        foreach ($menuiseriesExterieures as $menuiserie){
            $em->remove($menuiserie);
        }
        $em->flush();
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $pathHelper->calculPrixGoAndProjet($grosOeuvre);
        $prixApresCalculGo=$grosOeuvre->getPrix();
        $pathHelper->showNotifPriceGrosOeuvre($prixAvantCalculGo,$prixApresCalculGo);
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_gros_oeuvre_profile', [
            'id'=>$grosOeuvre->getId()
        ]);
    }

    /**
     * @Route("/liste/{idGrosOeuvre}", name="home_construct_menuiserie_exterieure_list")
     * @ParamConverter("grosOeuvre", options={"mapping": {"idGrosOeuvre": "id"}})
     */
    public function listAction(Request $request, GrosOeuvre $grosOeuvre)
    {
        $page = array(
            'title' => 'Gros Oeuvre',
            'sub_title' => 'Vos menuiseries extérieures'
        );
        $titreOnglet = "Menuiserie extérieure";

        $object = 'Menuiserie Exterieure';

        $entities = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('HomeConstructBuildBundle:MenuiserieExterieure')
            ->findBy(['grosOeuvre'=>$grosOeuvre]);

        // l'utilisateur est redirigé vers la vue d'affichage des listes
        return $this->render('@HomeConstructBuild/MenuiserieExterieure/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'grosOeuvre' => $grosOeuvre,
            'object' => $object,
            'titreOnglet' => $titreOnglet
        ));

    }
}
