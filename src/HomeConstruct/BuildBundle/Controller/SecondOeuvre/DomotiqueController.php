<?php 

namespace HomeConstruct\BuildBundle\Controller\SecondOeuvre;

use HomeConstruct\BuildBundle\Entity\SecondOeuvre;
use HomeConstruct\BuildBundle\Entity\Domotique;
use HomeConstruct\BuildBundle\Form\DomotiqueType;
use HomeConstruct\BuildBundle\Form\ProjetType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use HomeConstruct\BuildBundle\Entity\Projet;
use Symfony\Component\HttpFoundation\Request;

/**
 * Domotique controller.
 *
 * @Route("/second-oeuvre/domotique")
 */
class DomotiqueController extends Controller{

	/**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/edit/{idDomotique}", name="home_construct_domotique_edit")
     * @ParamConverter("domotique", options={"mapping": {"idDomotique": "id"}})
     * @Route("/creation/{idSecondOeuvre}", name="home_construct_domotique_creation")
     * @ParamConverter("secondOeuvre", options={"mapping": {"idSecondOeuvre": "id"}})
     */
    public function formAction(Request $request, SecondOeuvre $secondOeuvre=null, Domotique $domotique=null)
    {
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));
        if(!$domotique){
            $domotique = new Domotique($this->getDoctrine()->getManager());
            $page = array(
                'title' => 'Second Oeuvre',
                'sub_title' => 'Ajout de domotique(s)'
            );
            $titreOnglet = "Ajout Domotique";
        }else{
            $secondOeuvre = $domotique->getSecondOeuvre();
            $page = array(
                'title' => 'Second Oeuvre',
                'sub_title' => 'Modification de domotique(s)'
            );
            $titreOnglet = "Modification Domotique";
            $domotique->setEm($this->getDoctrine()->getManager());
            $ancienType=$domotique->getType();
        }
        $entities= $secondOeuvre->getDomotiques();
        $form = $this->createForm(DomotiqueType::class, $domotique);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $prixAvantCalculSO=$secondOeuvre->getPrix();
            //on teste si il existe pas deja la meme domotique avec les memes parametres
            $em = $this->getDoctrine()->getManager();
            $domotiquesSecondOeuvre=$em->getRepository('HomeConstructBuildBundle:Domotique')
                ->findBy(['secondOeuvre'=>$secondOeuvre]);
            if($domotiquesSecondOeuvre != null){
                $existe=false;
                $toRemove=false;
                /* on teste ici s'il existe une domotique deja
                existante dans le second oeuvre qui a les memes attributs
                que la domotique que l'on vient de créer/modifier
                si elle existe alors on modifie seulement la quantité de celle existante
                */
                foreach ($domotiquesSecondOeuvre as $domotiqueSecondOeuvre){
                    if($domotiqueSecondOeuvre->getId()!=$domotique->getId()) {
                        if ($domotiqueSecondOeuvre->getType() == $domotique->getType()) {
                            $domotiqueSecondOeuvre->setQuantite($domotique->getQuantite() + $domotiqueSecondOeuvre->getQuantite());
                            $domotiqueSecondOeuvre->setPrix(($domotique->getQuantite() + $domotiqueSecondOeuvre->getQuantite())*$domotiqueSecondOeuvre->getType()->getPrix());
                            $existe = true;
                            if ($domotique->getId()) {
                                $toRemove = true;
                            }
                            $em->persist($domotiqueSecondOeuvre);
                            $em->flush();
                            break; //on sort du foreach
                        }
                    }
                }
                /* si toRemove est true alors on doit supprimer la domotique
                que l'on vient de modifier car elle existe deja (on ajoutera juste sa quantité
                à celle deja existante)
                */
                if(!$domotique->getId()){
                    $notif = $domotique->getQuantite()." ".$domotique->getType()->getNom()." ajouté(e)(s)";
                }else{
                    $notif = "Domotique modifiée";
                }
                if($toRemove){
                    $em->remove($domotique);
                    $em->flush();
                }
                if(!$existe){
                    $domotique->setSecondOeuvre($secondOeuvre);
                    $em->persist($domotique);
                    $em->flush();
                }
            }else{
                if(!$domotique->getId()){
                    $notif = $domotique->getType()->getNom()." ajouté";
                }else{
                    $notif = "Domotique modifiée";
                }
                $domotique->setSecondOeuvre($secondOeuvre);
                $em->persist($domotique);
                $em->flush();
            }
            $prixApresCalculSO=$secondOeuvre->getPrix();
            $pathHelper->showNotifPriceSecondOeuvre($prixAvantCalculSO,$prixApresCalculSO);
            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_domotique_creation',[
                'idSecondOeuvre'=>$secondOeuvre->getId()
            ]);
        };

        return $this->render('@HomeConstructBuild/Domotique/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'secondOeuvre' => $secondOeuvre,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     * @Route("/liste/{idSecondOeuvre}", name="home_construct_domotique_list")
     * @ParamConverter("secondOeuvre", options={"mapping": {"idSecondOeuvre": "id"}})
     */
    public function listAction(Request $request, SecondOeuvre $secondOeuvre)
    {
        $page = array(
            'title' => 'Second Oeuvre',
            'sub_title' => 'Vos domotiques'
        );

        $titreOnglet = "Domotiques";

        $object = 'Domotique';
        $entities = $secondOeuvre->getDomotiques();
        // l'utilisateur est redirigé vers la vue d'affichage des listes
        return $this->render('@HomeConstructBuild/Domotique/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'secondOeuvre' => $secondOeuvre,
            'object' => $object,
            'titreOnglet' => $titreOnglet
        ));
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_domotique_deleteone")
     */
    public function deleteOneAction(Request $request, Domotique $domotique)
    {
        $notif = $domotique->getQuantite()." ".$domotique->getType()->getNom()." supprimé(e)";
        $secondOeuvre=$domotique->getSecondOeuvre();
        $prixAvantCalculSo=$secondOeuvre->getPrix();
        $em = $this->getDoctrine()->getManager();
        $em->remove($domotique);
        $em->flush();
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $pathHelper->calculPrixSoAndProjet($secondOeuvre);
        $prixApresCalculSo=$secondOeuvre->getPrix();
        $pathHelper->showNotifPriceSecondOeuvre($prixAvantCalculSo,$prixApresCalculSo);
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_domotique_list', [
            'idSecondOeuvre'=>$secondOeuvre->getId()
        ]);
    }

    /**
     * @Route("/suppression-domotiques/{idSecondOeuvre}", name="home_construct_domotique_deleteall")
     * @ParamConverter("secondOeuvre", options={"mapping": {"idSecondOeuvre": "id"}})
     */
    public function deleteAllAction(Request $request, SecondOeuvre $secondOeuvre)
    {
        $prixAvantCalculSo=$secondOeuvre->getPrix();
        $em = $this->getDoctrine()->getManager();
        $notif = "Informations sur les domotiques supprimées";
        $domotiques=$secondOeuvre->getDomotiques();
        foreach ($domotiques as $domotique){
            $em->remove($domotique);
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