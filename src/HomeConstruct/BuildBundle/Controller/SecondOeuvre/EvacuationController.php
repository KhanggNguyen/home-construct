<?php 

namespace HomeConstruct\BuildBundle\Controller\SecondOeuvre;

use HomeConstruct\BuildBundle\Entity\SecondOeuvre;
use HomeConstruct\BuildBundle\Entity\Evacuation;
use HomeConstruct\BuildBundle\Form\EvacuationType;
use HomeConstruct\BuildBundle\Form\ProjetType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use HomeConstruct\BuildBundle\Entity\Projet;
use Symfony\Component\HttpFoundation\Request;

/**
 * Evacuation controller.
 *
 * @Route("/second-oeuvre/evacuation")
 */
class EvacuationController extends Controller{

	/**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/edit/{idEvacuation}", name="home_construct_evacuation_edit")
     * @ParamConverter("evacuation", options={"mapping": {"idEvacuation": "id"}})
     * @Route("/creation/{idSecondOeuvre}", name="home_construct_evacuation_creation")
     * @ParamConverter("secondOeuvre", options={"mapping": {"idSecondOeuvre": "id"}})
     */
    public function formAction(Request $request, SecondOeuvre $secondOeuvre=null, Evacuation $evacuation=null)
    {
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));
        if(!$evacuation){
            $evacuation = new Evacuation($this->getDoctrine()->getManager());
            $page = array(
                'title' => 'Second Oeuvre',
                'sub_title' => 'Ajout d\'une évacuation'
            );
            $titreOnglet = "Ajout Evacuation";
        }else{
            $secondOeuvre = $evacuation->getSecondOeuvre();
            $evacuation->setEm($this->getDoctrine()->getManager());
            $page = array(
                'title' => 'Second Oeuvre',
                'sub_title' => 'Modification d\'une évacuation'
            );
            $titreOnglet = "Modification Evacuation";
            $ancienType=$evacuation->getType();
        }
        $entities= $secondOeuvre->getEvacuations();
        $form = $this->createForm(EvacuationType::class, $evacuation);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $prixAvantCalculSO=$secondOeuvre->getPrix();
            //on teste si il existe pas deja la meme evacuation avec les memes parametres
            $em = $this->getDoctrine()->getManager();
            $evacuationsSecondOeuvre=$em->getRepository('HomeConstructBuildBundle:Evacuation')
                ->findBy(['secondOeuvre'=>$secondOeuvre]);
            if($evacuationsSecondOeuvre != null){
                $existe=false;
                $toRemove=false;
                /* on teste ici s'il existe une evacuation deja
                existante dans le second oeuvre qui a les memes attributs
                que la evacuation que l'on vient de créer/modifier
                si elle existe alors on modifie seulement la quantité de celle existante
                */
                foreach ($evacuationsSecondOeuvre as $evacuationSecondOeuvre){
                    if($evacuationSecondOeuvre->getId()!=$evacuation->getId()) {
                        if ($evacuationSecondOeuvre->getType() == $evacuation->getType()) {
                            $evacuationSecondOeuvre->setQuantite($evacuation->getQuantite() + $evacuationSecondOeuvre->getQuantite());
                            $evacuationSecondOeuvre->setPrix(($evacuation->getQuantite() + $evacuationSecondOeuvre->getQuantite())*$evacuationSecondOeuvre->getType()->getPrix());
                            $existe = true;
                            if ($evacuation->getId()) {
                                $toRemove = true;
                            }
                            $evacuationSecondOeuvre->setEm($em);
                            $em->persist($evacuationSecondOeuvre);
                            $em->flush();
                            break; //on sort du foreach
                        }
                    }
                }
                /* si toRemove est true alors on doit supprimer la evacuation
                que l'on vient de modifier car elle existe deja (on ajoutera juste sa quantité
                à celle deja existante)
                */
                if(!$evacuation->getId()){
                    $notif = $evacuation->getQuantite()." ".$evacuation->getType()->getNom()." ajouté(e)(s)";
                }else{
                    $notif = "Evacuation modifiée";
                }
                if($toRemove){
                    $em->remove($evacuation);
                    $em->flush();
                }
                if(!$existe){
                    $evacuation->setSecondOeuvre($secondOeuvre);
                    $em->persist($evacuation);
                    $em->flush();
                }
            }else{
                if(!$evacuation->getId()){
                    $notif = $evacuation->getQuantite()." ".$evacuation->getType()->getNom()." ajouté(e)(s)";
                }else{
                    $notif = "Evacuation modifiée";
                }
                $evacuation->setSecondOeuvre($secondOeuvre);
                $em->persist($evacuation);
                $em->flush();
            }
            $prixApresCalculSO=$secondOeuvre->getPrix();
            $pathHelper->showNotifPriceSecondOeuvre($prixAvantCalculSO,$prixApresCalculSO);
            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_evacuation_creation',[
                'idSecondOeuvre'=>$secondOeuvre->getId()
            ]);
        };

        return $this->render('@HomeConstructBuild/Evacuation/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'secondOeuvre' => $secondOeuvre,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     * @Route("/liste/{idSecondOeuvre}", name="home_construct_evacuation_list")
     * @ParamConverter("secondOeuvre", options={"mapping": {"idSecondOeuvre": "id"}})
     */
    public function listAction(Request $request, SecondOeuvre $secondOeuvre)
    {
        $page = array(
            'title' => 'Second Oeuvre',
            'sub_title' => 'Vos évacuations'
        );

        $titreOnglet = "Evacuations";

        $object = 'Evacuation';
        $entities = $secondOeuvre->getEvacuations();
        // l'utilisateur est redirigé vers la vue d'affichage des listes
        return $this->render('@HomeConstructBuild/Evacuation/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'secondOeuvre' => $secondOeuvre,
            'object' => $object,
            'titreOnglet' => $titreOnglet
        ));
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_evacuation_deleteone")
     */
    public function deleteOneAction(Request $request, Evacuation $evacuation)
    {
        $notif = $evacuation->getQuantite()." ".$evacuation->getType()->getNom()." supprimé(e)";
        $secondOeuvre=$evacuation->getSecondOeuvre();
        $prixAvantCalculSo=$secondOeuvre->getPrix();
        $em = $this->getDoctrine()->getManager();
        $em->remove($evacuation);
        $em->flush();
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $pathHelper->calculPrixSoAndProjet($secondOeuvre);
        $prixApresCalculSo=$secondOeuvre->getPrix();
        $pathHelper->showNotifPriceSecondOeuvre($prixAvantCalculSo,$prixApresCalculSo);
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_evacuation_list', [
            'idSecondOeuvre'=>$secondOeuvre->getId()
        ]);
    }

    /**
     * @Route("/suppression-evacuations/{idSecondOeuvre}", name="home_construct_evacuation_deleteall")
     * @ParamConverter("secondOeuvre", options={"mapping": {"idSecondOeuvre": "id"}})
     */
    public function deleteAllAction(Request $request, SecondOeuvre $secondOeuvre)
    {
        $prixAvantCalculSo=$secondOeuvre->getPrix();
        $em = $this->getDoctrine()->getManager();
        $notif = "Informations sur les evacuations supprimées";
        $evacuations=$secondOeuvre->getEvacuations();
        foreach ($evacuations as $evacuation){
            $em->remove($evacuation);
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