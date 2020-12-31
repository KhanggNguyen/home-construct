<?php 

namespace HomeConstruct\BuildBundle\Controller\SecondOeuvre;

use HomeConstruct\BuildBundle\Entity\SecondOeuvre;
use HomeConstruct\BuildBundle\Entity\Ventilation;
use HomeConstruct\BuildBundle\Form\VentilationType;
use HomeConstruct\BuildBundle\Form\ProjetType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use HomeConstruct\BuildBundle\Entity\Projet;
use Symfony\Component\HttpFoundation\Request;

/**
 * Ventilation controller.
 *
 * @Route("/second-oeuvre/ventilation")
 */
class VentilationController extends Controller{

	/**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/edit/{idVentilation}", name="home_construct_ventilation_edit")
     * @ParamConverter("ventilation", options={"mapping": {"idVentilation": "id"}})
     * @Route("/creation/{idSecondOeuvre}", name="home_construct_ventilation_creation")
     * @ParamConverter("secondOeuvre", options={"mapping": {"idSecondOeuvre": "id"}})
     */
    public function formAction(Request $request, SecondOeuvre $secondOeuvre=null, Ventilation $ventilation=null)
    {
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));
        if(!$ventilation){
            $ventilation = new Ventilation($this->getDoctrine()->getManager());
            $page = array(
                'title' => 'Second Oeuvre',
                'sub_title' => 'Ajout d\'une ventilation'
            );
            $titreOnglet = "Ajout Ventilation";
        }else{
            $secondOeuvre = $ventilation->getSecondOeuvre();
            $page = array(
                'title' => 'Second Oeuvre',
                'sub_title' => 'Modification d\'une ventilation'
            );
            $ventilation->setEm($this->getDoctrine()->getManager());
            $titreOnglet = "Modification Ventilation";
            $ancienType=$ventilation->getType();
        }
        $entities= $secondOeuvre->getVentilations();
        $form = $this->createForm(VentilationType::class, $ventilation);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $prixAvantCalculSO=$secondOeuvre->getPrix();
            //on teste si il existe pas deja la meme ventilation avec les memes parametres
            $em = $this->getDoctrine()->getManager();
            $ventilationsSecondOeuvre=$em->getRepository('HomeConstructBuildBundle:Ventilation')
                ->findBy(['secondOeuvre'=>$secondOeuvre]);
            if($ventilationsSecondOeuvre != null){
                $existe=false;
                $toRemove=false;
                /* on teste ici s'il existe une ventilation deja
                existante dans le second oeuvre qui a les memes attributs
                que la ventilation que l'on vient de créer/modifier
                si elle existe alors on modifie seulement la quantité de celle existante
                */
                foreach ($ventilationsSecondOeuvre as $ventilationSecondOeuvre){
                    if($ventilationSecondOeuvre->getId()!=$ventilation->getId()) {
                        if ($ventilationSecondOeuvre->getType() == $ventilation->getType()) {
                            $ventilationSecondOeuvre->setQuantite($ventilation->getQuantite() + $ventilationSecondOeuvre->getQuantite());
                            $ventilationSecondOeuvre->setPrix(($ventilation->getQuantite() + $ventilationSecondOeuvre->getQuantite())*$ventilationSecondOeuvre->getType()->getPrix());
                            $existe = true;
                            if ($ventilation->getId()) {
                                $toRemove = true;
                            }
                            $ventilationSecondOeuvre->setEm($em);
                            $em->persist($ventilationSecondOeuvre);
                            $em->flush();
                            break; //on sort du foreach
                        }
                    }
                }
                /* si toRemove est true alors on doit supprimer la ventilation
                que l'on vient de modifier car elle existe deja (on ajoutera juste sa quantité
                à celle deja existante)
                */
                if(!$ventilation->getId()){
                    $notif = $ventilation->getQuantite()." ventilation(s) ".$ventilation->getType()->getNom()." ajouté(s)";
                }else{
                    $notif = "Ventilation modifiée";
                }
                if($toRemove){
                    $em->remove($ventilation);
                    $em->flush();
                }
                if(!$existe){
                    $ventilation->setSecondOeuvre($secondOeuvre);
                    $em->persist($ventilation);
                    $em->flush();
                }
            }else{
                if(!$ventilation->getId()){
                    $notif = $ventilation->getQuantite()." ".$ventilation->getType()->getNom()." ajouté(s)";
                }else{
                    $notif = "Ventilation modifiée";
                }
                $ventilation->setSecondOeuvre($secondOeuvre);
                $em->persist($ventilation);
                $em->flush();
            }
            $prixApresCalculSO=$secondOeuvre->getPrix();
            $pathHelper->showNotifPriceSecondOeuvre($prixAvantCalculSO,$prixApresCalculSO);
            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_ventilation_creation',[
                'idSecondOeuvre'=>$secondOeuvre->getId()
            ]);
        };

        return $this->render('@HomeConstructBuild/Ventilation/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'secondOeuvre' => $secondOeuvre,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     * @Route("/liste/{idSecondOeuvre}", name="home_construct_ventilation_list")
     * @ParamConverter("secondOeuvre", options={"mapping": {"idSecondOeuvre": "id"}})
     */
    public function listAction(Request $request, SecondOeuvre $secondOeuvre)
    {
        $pathHelper=new PathHelper();
        $refererIsProfilePiece=$pathHelper->pathIsProfilePiece($request->headers->get('referer'));
        $page = array(
            'title' => 'Second Oeuvre',
            'sub_title' => 'Vos ventilations'
        );

        $titreOnglet = "Ventilations";

        $object = 'Ventilation';
        $entities = $secondOeuvre->getVentilations();
        // l'utilisateur est redirigé vers la vue d'affichage des listes
        return $this->render('@HomeConstructBuild/Ventilation/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'secondOeuvre' => $secondOeuvre,
            'object' => $object,
            'titreOnglet' => $titreOnglet,
            'refererIsProfilePiece'=>$refererIsProfilePiece
        ));
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_ventilation_deleteone")
     */
    public function deleteOneAction(Request $request, Ventilation $ventilation)
    {
        $notif = $ventilation->getQuantite()." ventilation(s) ".$ventilation->getType()->getNom()." supprimé(e)";
        $secondOeuvre=$ventilation->getSecondOeuvre();
        $prixAvantCalculSo=$secondOeuvre->getPrix();
        $em = $this->getDoctrine()->getManager();
        $em->remove($ventilation);
        $em->flush();
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $pathHelper->calculPrixSoAndProjet($secondOeuvre);
        $prixApresCalculSo=$secondOeuvre->getPrix();
        $pathHelper->showNotifPriceSecondOeuvre($prixAvantCalculSo,$prixApresCalculSo);
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_ventilation_list', [
            'idSecondOeuvre'=>$secondOeuvre->getId()
        ]);
    }

    /**
     * @Route("/suppression-ventilations/{idSecondOeuvre}", name="home_construct_ventilation_deleteall")
     * @ParamConverter("secondOeuvre", options={"mapping": {"idSecondOeuvre": "id"}})
     */
    public function deleteAllAction(Request $request, SecondOeuvre $secondOeuvre)
    {
        $prixAvantCalculSo=$secondOeuvre->getPrix();
        $em = $this->getDoctrine()->getManager();
        $notif = "Informations sur les ventilations supprimées";
        $ventilations=$secondOeuvre->getVentilations();
        foreach ($ventilations as $ventilation){
            $em->remove($ventilation);
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


}