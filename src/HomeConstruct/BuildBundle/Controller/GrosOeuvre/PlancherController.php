<?php
/**
 * Created by PhpStorm.
 * User: kweidner
 * Date: 08/03/2019
 * Time: 21:48
 */

namespace HomeConstruct\BuildBundle\Controller\GrosOeuvre;

use HomeConstruct\BuildBundle\Entity\GrosOeuvre;
use HomeConstruct\BuildBundle\Entity\Plancher;

use HomeConstruct\BuildBundle\Form\PlancherType;
use HomeConstruct\BuildBundle\Form\ProjetType;
use Doctrine\Common\Persistence\ObjectManager;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use HomeConstruct\BuildBundle\Entity\Projet;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\FormTypeInterface;

/**
 * Plancher controller.
 *
 * @Route("/gros-oeuvre/plancher")
 */
class PlancherController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/edit/{idGrosOeuvre}", name="home_construct_plancher_edit")
     * @ParamConverter("plancher", options={"mapping": {"idGrosOeuvre": "id"}})
     * @Route("/creation/{idGrosOeuvre}", name="home_construct_plancher_creation")
     * @ParamConverter("grosOeuvre", options={"mapping": {"idGrosOeuvre": "id"}})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function formAction(Request $request, GrosOeuvre $grosOeuvre=null, Plancher $plancher=null)
    {
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));
        if(!$plancher){
            $plancher = new Plancher($this->getDoctrine()->getManager());
            $page = array(
                'title' => 'Gros Oeuvre',
                'sub_title' => 'Ajout de plancher(s)'
            );
            $titreOnglet = "Ajout Plancher";
        }else{
            $grosOeuvre = $plancher->getGrosOeuvre();
            $page = array(
                'title' => 'Gros Oeuvre',
                'sub_title' => 'Modification de plancher(s)'
            );
            $plancher->setEm($this->getDoctrine()->getManager());
            $titreOnglet = "Modification Plancher";
        }
        $entities= $grosOeuvre->getPlancher();
        $form = $this->createForm(PlancherType::class, $plancher);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $prixAvantCalculGO=$grosOeuvre->getPrix();
            $em = $this->getDoctrine()->getManager();
            $plancherGrosOeuvre=$em->getRepository('HomeConstructBuildBundle:Plancher')
                ->findBy(['grosOeuvre'=>$grosOeuvre]);
            if($plancherGrosOeuvre != null){
                $existe=false;
                $toRemove=false;
                /* on teste ici s'il existe une menuiserie exterieure deja
                existante dans le gros oeuvre qui a les memes attributs
                que la menuiserie que l'on vient de créer/modifier
                si elle existe alors on modifie seulement la quantité de celle existante
                */
                foreach ($plancherGrosOeuvre as $leplancher){
                    if($leplancher->getId()!=$plancher->getId()) {
                        if ($leplancher->getType() == $plancher->getType()) {
                            if ($leplancher->getLongueurEntrevous() == $plancher->getLongueurEntrevous()) {
                                if ($leplancher->getNbM2() == $plancher->getNbM2()) {
                                    if ($leplancher->getLongueurPoutrelle() == $plancher->getLongueurPoutrelle()) {
                                        $leplancher->setQuantite($leplancher->getQuantite() + $plancher->getQuantite());
                                        $existe = true;
                                        if ($plancher->getId()) {
                                            $toRemove = true;
                                        }
                                        $em->persist($leplancher);
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
                if(!$plancher->getId()){
                    $notif = $plancher->getQuantite()." ".$plancher->getType()." ajouté(e)(s)";
                }else{
                    $notif = "Plancher modifiée";
                }
                if($toRemove){
                    $em->remove($leplancher);
                    $em->flush();
                }
                if(!$existe){
                    $plancher->setGrosOeuvre($grosOeuvre);
                    $em->persist($plancher);
                    $em->flush();
                }
            }else{
                if(!$plancher->getId()){
                    $notif = $plancher->getQuantite()." ".$plancher->getType()->getNom()." ajouté";
                }else{
                    $notif = "Plancher modifiée";
                }
                $plancher->setGrosOeuvre($grosOeuvre);
                $em->persist($plancher);
                $em->flush();
            }
            $prixApresCalculGO=$grosOeuvre->getPrix();
            $pathHelper->showNotifPriceGrosOeuvre($prixAvantCalculGO,$prixApresCalculGO);
            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_plancher_list',[
                'idGrosOeuvre'=>$grosOeuvre->getId()
            ]);
        };
        /*$type=$request->request->get('homeconstruct_buildbundle_menuiserieexterieure')['type'];
        $dimensionLongueur=$request->request->get('homeconstruct_buildbundle_menuiserieexterieure')['dimension']['longueur'];
        $dimensionLargeur=$request->request->get('homeconstruct_buildbundle_menuiserieexterieure')['dimension']['largeur'];
        $materiaux=$request->request->get('homeconstruct_buildbundle_menuiserieexterieure')['materiaux'];
        $quantite=$request->request->get('homeconstruct_buildbundle_menuiserieexterieure')['quantite'];*/

        return $this->render('@HomeConstructBuild/Plancher/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'grosOeuvre' => $grosOeuvre,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_plancher_deleteone")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteOneAction(Request $request, Plancher $plancher)
    {
        $notif = $plancher->getQuantite()." ".$plancher->getType()->getNom()." supprimé(e)(s)";
        $grosOeuvre=$plancher->getGrosOeuvre();
        $prixAvantCalculGo=$grosOeuvre->getPrix();
        $em = $this->getDoctrine()->getManager();
        $em->remove($plancher);
        $em->flush();
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $pathHelper->calculPrixGoAndProjet($grosOeuvre);
        $prixApresCalculGo=$grosOeuvre->getPrix();
        $pathHelper->showNotifPriceGrosOeuvre($prixAvantCalculGo,$prixApresCalculGo);
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_plancher_list', [
            'idGrosOeuvre'=>$grosOeuvre->getId()
        ]);
    }

    /**
     * @Route("/suppression-planchers/{idGrosOeuvre}", name="home_construct_plancher_deleteall")
     * @ParamConverter("grosOeuvre", options={"mapping": {"idGrosOeuvre": "id"}})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAllAction(Request $request, GrosOeuvre $grosOeuvre)
    {
        $prixAvantCalculGo=$grosOeuvre->getPrix();
        $em = $this->getDoctrine()->getManager();
        $notif = "Informations sur les planchers supprimées";
        $planchers=$grosOeuvre->getPlancher();
        foreach ($planchers as $plancher){
            $em->remove($plancher);
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
     * @Route("/liste/{idGrosOeuvre}", name="home_construct_plancher_list")
     * @ParamConverter("grosOeuvre", options={"mapping": {"idGrosOeuvre": "id"}})
     */
    public function listAction(Request $request, GrosOeuvre $grosOeuvre)
    {
        $page = array(
            'title' => 'Gros Oeuvre',
            'sub_title' => 'Vos planchers'
        );

        $titreOnglet = "Planchers";

        $object = 'Plancher';

        $entities = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('HomeConstructBuildBundle:Plancher')
            ->findBy(['grosOeuvre'=>$grosOeuvre]);

        // l'utilisateur est redirigé vers la vue d'affichage des listes
        return $this->render('@HomeConstructBuild/Plancher/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'grosOeuvre' => $grosOeuvre,
            'object' => $object,
            'titreOnglet' => $titreOnglet
        ));

    }
}
