<?php

namespace HomeConstruct\BuildBundle\Controller\GrosOeuvre;

use HomeConstruct\BuildBundle\Entity\GrosOeuvre;
use HomeConstruct\BuildBundle\Entity\Piece;
use HomeConstruct\BuildBundle\Form\EtudeSolType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use HomeConstruct\BuildBundle\Entity\EtudeSol;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * EtudeSol controller.
 *
 * @Route("/gros-oeuvre/etude-sol")
 */
class EtudeSolController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/{idGrosOeuvre}", name="home_construct_etude_sol_creation")
     * @ParamConverter("grosOeuvre", options={"mapping": {"idGrosOeuvre": "id"}})
     * @Route("/edit/{idEtudeSol}", name="home_construct_etude_sol_edit")
     * @ParamConverter("etudeSol", options={"mapping": {"idEtudeSol": "id"}})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function formAction(Request $request,EtudeSol $etudeSol=null,GrosOeuvre $grosOeuvre=null)
    {
        // ici on stocke dans la session l'url d'ou l'on vient au moment ou on arrive sur le formulaire
        // si l'attribut 'refererBeforeForm' (crée par moi) n'est pas égale à null cela veut dire
        // qu'on a deja fait l'initialisation (par exemple quand on submit le form on repassera par cette fonction)

        if($request->getSession()->get('refererBeforeForm')==null){
            $request->getSession()->set('refererBeforeForm',$request->headers->get('referer'));
        }

        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper= new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $typesSol=$this->getDoctrine()->getManager()
                ->getRepository('HomeConstructBuildBundle:TypeSol')
                ->findAll();
        if(!$etudeSol){
            $page = array(
                'title' => 'Gros Oeuvre',
                'sub_title' => 'Ajout de l\'étude du sol'
            );
            $titreOnglet = "Modif Gros Oeuvre";
            $etudeSol = new EtudeSol($this->getDoctrine()->getManager());
        }else{
            $page = array(
                'title' => 'Gros Oeuvre',
                'sub_title' => 'Modification de l\'étude du sol'
            );
            $titreOnglet = "Modif Gros Oeuvre";
            $grosOeuvre=$etudeSol->getGrosOeuvre();
            $etudeSol->setEm($this->getDoctrine()->getManager());
        }
        $form = $this->createForm(EtudeSolType::class, $etudeSol);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!$etudeSol->getId()){
                $grosOeuvre->setEtudeSol($etudeSol);
                $etudeSol->setGrosOeuvre($grosOeuvre);
                $etudeSol->setCreateur($this->getUser());
                $notif='Informations sur l\'etude du sol ajoutées';
                $creation=true;
            }else{
                $notif='Informations sur l\'etude du sol modifiées';
                $creation=false;
                $etudeSol->setModifieur($this->getUser());
                $lastPrice=$etudeSol->getPrixForfait();
            }
            $em->persist($etudeSol);
            $prixGOAvantCalcul=$grosOeuvre->getPrix();
            $em->persist($grosOeuvre);

            // si le soubassement a deja été renseigné on recalcule son prix
            if($grosOeuvre->getSoubassement() or $grosOeuvre->getFondation()){
                $tab=array($grosOeuvre->getSoubassement(),$grosOeuvre->getFondation());
                $pathHelper->calculPrixAfterEditGo($tab,$grosOeuvre);
            }
            $em->flush();
            $projet=$grosOeuvre->getProjet();
            $em->persist($projet);
            $em->flush();
            // si on a modifié l'étude de sol on affichera la notif avec l'ancien prix et le nouveau
            if(isset($lastPrice)){
                $newPrice=$etudeSol->getPrixForfait();
                $pathHelper->showNotifPriceChange($etudeSol,$lastPrice,$newPrice);
            }

            $prixGOApresCalcul=$grosOeuvre->getPrix();
            // cette fonction affichera une notif pour afficher le chgt de prix du gros oeuvre s'il y a
            $pathHelper->showNotifPriceGrosOeuvre($prixGOAvantCalcul,$prixGOApresCalcul);
            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');

            // si c'est une création alors on va comparer l'url du profile soubassement
            // et l'url de la page où on etait juste avant d'arriver sur le form
            if($grosOeuvre->getSoubassement()){
                if($creation){
                    $urlProfileSoubassement="http://".$request->getHost().$this->generateUrl('home_construct_soubassement_profile', [
                            'id'=>$grosOeuvre->getSoubassement()->getId()
                        ]);
                    $referer=$request->getSession()->get('refererBeforeForm');
                    // si ils sont égaux cela veut dire qu'on doit etre redirigé vers le profil soubassement
                    // car on a cliqué sur le lien qui permet de créer une etude sol (pour l'estimation prix)
                    if($urlProfileSoubassement==$referer){
                        $grosOeuvre->getSoubassement()->calculPrix();
                        $em->persist($grosOeuvre->getSoubassement());
                        $em->flush();
                        $request->getSession()->remove('refererBeforeForm');
                        return $this->redirect($referer);
                    }
                }
            }
            // fonction qui envoie des mails de notif aux clients du projet
            $pathHelper->sendMailToClientsWhenEdit(
                $this->get('symracine_mail.mailer'),
                $this->getUser(),
                $grosOeuvre,
                $prixGOAvantCalcul,
                $prixGOApresCalcul,
                $creation,
                'HomeConstructBuildBundle:Mail:etude_sol_edited'
            );
            if($request->getSession()->get('refererBeforeForm')){
                $request->getSession()->remove('refererBeforeForm');
            }
            return $this->redirectToRoute('home_construct_etude_sol_profile',[
                'id'=>$etudeSol->getId()
            ]);
        };

        return $this->render('@HomeConstructBuild/EtudeSol/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'editMode' => $etudeSol->getId() !== null,
            'grosOeuvre' =>$grosOeuvre,
            'typesSol'=>$typesSol,
            'refererIsAForm' =>$refererIsAForm
        ]);
    }


    /**
     * @Route("/suppression/{id}", name="home_construct_etude_sol_delete")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request, EtudeSol $etudeSol)
    {
        $grosOeuvre=$this->getDoctrine()
            ->getManager()
            ->getRepository('HomeConstructBuildBundle:GrosOeuvre')
            ->findOneBy(['etudeSol'=>$etudeSol]);
        $em = $this->getDoctrine()->getManager();
        $em->remove($etudeSol);
        $em->flush();
        $pathHelper=new PathHelper($em,$request);
        $pathHelper->sendMailToClientsWhenDelete(
            $this->get('symracine_mail.mailer'),
            $this->getUser(),
            $grosOeuvre,
            'HomeConstructBuildBundle:Mail:etude_sol_deleted'
        );
        $request->getSession()->set('entity-deleted','etude-sol');
        $request->getSession()->getFlashBag()->add('notice', 'Informations sur l\'etude du sol supprimées');
        $request->getSession()->getFlashBag()->add('notif', 'True');
        if($grosOeuvre->getFondation()){
            $request->getSession()->getFlashBag()->add('nameEntity1', 'Fondations');
            $request->getSession()->getFlashBag()->add('newPrice1', 'Nouveau prix total calculé');
            $request->getSession()->getFlashBag()->add('calcul1', 'True');
        }
        if($grosOeuvre->getSoubassement()){
            $request->getSession()->getFlashBag()->add('nameEntity2', 'Soubassement');
            $request->getSession()->getFlashBag()->add('newPrice2', 'Nouveau prix total calculé');
            $request->getSession()->getFlashBag()->add('calcul2', 'True');
        }
        return $this->redirectToRoute('home_construct_gros_oeuvre_profile', [
            'id'=>$grosOeuvre->getId()
        ]);
    }

    /**
     * @Route("/profile/view/{id}", name="home_construct_etude_sol_profile")
     */
    public function profileAction(Request $request, EtudeSol $etudeSol)
    {
        $grosOeuvre=$this->getDoctrine()
            ->getManager()
            ->getRepository('HomeConstructBuildBundle:GrosOeuvre')
            ->findOneBy(['etudeSol'=>$etudeSol]);

        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=$this->container->get('homeconstruct_service.pathhelper');
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));
        $page = array(
            'title' => 'Gros Oeuvre',
            'sub_title' => 'Infos sur l\'étude du sol'
        );
        $titreOnglet = "Etude du sol";

        $object = 'EtudeSol';

        return $this->render('@HomeConstructBuild/EtudeSol/profile.html.twig', array(
            'page' => $page,
            'object' => $object,
            'etude' => $etudeSol,
            'titreOnglet' => $titreOnglet,
            'grosOeuvre' => $grosOeuvre,
            'refererIsAForm'=>$refererIsAForm
        ));
    }
}

