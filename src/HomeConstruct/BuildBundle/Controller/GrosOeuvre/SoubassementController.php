<?php

namespace HomeConstruct\BuildBundle\Controller\GrosOeuvre;

use HomeConstruct\BuildBundle\Entity\GrosOeuvre;
use HomeConstruct\BuildBundle\Entity\Piece;
use HomeConstruct\BuildBundle\Form\SoubassementType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use HomeConstruct\BuildBundle\Entity\Soubassement;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Soubassement controller.
 *
 * @Route("/gros-oeuvre/soubassement")
 */
class SoubassementController extends Controller
{
    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/{idGrosOeuvre}", name="home_construct_soubassement_creation")
     * @ParamConverter("grosOeuvre", options={"mapping": {"idGrosOeuvre": "id"}})
     * @Route("/edit/{idSoubassement}", name="home_construct_soubassement_edit")
     * @ParamConverter("soubassement", options={"mapping": {"idSoubassement": "id"}})
     */
    public function formAction(Request $request,GrosOeuvre $grosOeuvre=null,Soubassement $soubassement=null)
    {
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));
        $typesSoubassement=$this->getDoctrine()->getManager()->getRepository('HomeConstructBuildBundle:TypeSoubassement')->findAll();

        if(!$soubassement){
            $page = array(
                'title' => 'Gros Oeuvre',
                'sub_title' => 'Ajout des infos sur le soubassement'
            );
            $titreOnglet = "Modif Gros Oeuvre";
            $soubassement = new Soubassement();
        }else{
            $page = array(
                'title' => 'Gros Oeuvre',
                'sub_title' => 'Modification des infos sur le soubassement'
            );
            $titreOnglet = "Modif Gros Oeuvre";
            $grosOeuvre=$soubassement->getGrosOeuvre();
        }
        $form = $this->createForm(SoubassementType::class, $soubassement);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $prixGOAvantCalcul=$grosOeuvre->getPrix();
            $em = $this->getDoctrine()->getManager();
            if(!$soubassement->getId()){
                $grosOeuvre->setSoubassement($soubassement);
                $soubassement->setGrosOeuvre($grosOeuvre);
                $notif="Informations sur le soubassement ajoutées";
            }else{
                $notif="Informations sur le soubassement modifiées";
                $lastPrice=$soubassement->getPrixTotal();
            }
            $em->persist($soubassement);
            $em->flush();


            // si les infos sur les fondations ont deja été renseignés on recalcule son prix
            if($grosOeuvre->getFondation()){
                $fondation=$grosOeuvre->getFondation();
                $prixAvantCalcul=$fondation->getPrix();
                $fondation->calculPrix();
                $em->persist($fondation);
                $em->flush();
                $prixApresCalcul=$fondation->getPrix();
                if($prixApresCalcul!=$prixAvantCalcul){
                    $request->getSession()->getFlashBag()->add('newPrice2', 'Le prix total est passé de '.$prixAvantCalcul.'€ à '.$prixApresCalcul.'€');
                    $request->getSession()->getFlashBag()->add('nameEntity2', 'Fondations');
                    $request->getSession()->getFlashBag()->add('calcul2', 'True');
                }
            }

            $grosOeuvre->calculPrix();
            $em->persist($grosOeuvre);
            $em->flush();
            $projet=$grosOeuvre->getProjet();
            $em->persist($projet);
            $em->flush();
            $prixGOApresCalcul=$grosOeuvre->getPrix();
            // affichage de la notif de chgt prix gros oeuvre
            $pathHelper->showNotifPriceGrosOeuvre($prixGOAvantCalcul,$prixGOApresCalcul);

            $projet=$grosOeuvre->getProjet();
            $projet->calculPrix();
            $em->persist($projet);
            $em->flush();
            if(isset($lastPrice)){
                $newPrice=$soubassement->getPrixTotal();
                $pathHelper->showNotifPriceChange($soubassement,$lastPrice,$newPrice);
            }

            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_soubassement_profile',[
                'id'=>$soubassement->getId()
            ]);
        };

        return $this->render('@HomeConstructBuild/Soubassement/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'editMode' => $soubassement->getId() !== null,
            'grosOeuvre' => $grosOeuvre,
            'soubassement' => $soubassement,
            'refererIsAForm'=>$refererIsAForm,
            'typesSoubassement'=>$typesSoubassement
        ]);
    }


    /**
     * @Route("/suppression/{id}", name="home_construct_soubassement_delete")
     */
    public function deleteAction(Request $request, Soubassement $soubassement)
    {
        $grosOeuvre=$this->getDoctrine()
            ->getManager()
            ->getRepository('HomeConstructBuildBundle:GrosOeuvre')
            ->findOneBy(['soubassement'=>$soubassement]);
        $em = $this->getDoctrine()->getManager();
        $em->remove($soubassement);
        $em->flush();

        $request->getSession()->set('entity-deleted','soubassement');
        $request->getSession()->getFlashBag()->add('notice', "Informations sur le soubassement supprimées");
        $request->getSession()->getFlashBag()->add('notif', 'True');
        $request->getSession()->getFlashBag()->add('newPrice1', 'Nouveau prix total calculé');
        $request->getSession()->getFlashBag()->add('nameEntity1', 'Fondations');
        $request->getSession()->getFlashBag()->add('calcul1', 'True');
        return $this->redirectToRoute('home_construct_gros_oeuvre_profile', [
            'id'=>$grosOeuvre->getId()
        ]);
    }

    /**
     * @Route("/profile/view/{id}", name="home_construct_soubassement_profile")
     */
    public function profileAction(Request $request, Soubassement $soubassement)
    {
        $grosOeuvre=$this->getDoctrine()
            ->getManager()
            ->getRepository('HomeConstructBuildBundle:GrosOeuvre')
            ->findOneBy(['soubassement'=>$soubassement]);

        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=$this->container->get('homeconstruct_service.pathhelper');
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        $page = array(
            'title' => 'Gros Oeuvre',
            'sub_title' => 'Infos sur le soubassement'
        );
        $titreOnglet = "Soubassement";

        $object = 'Soubassement';

        return $this->render('@HomeConstructBuild/Soubassement/profile.html.twig', array(
            'page' => $page,
            'object' => $object,
            'soubassement' => $soubassement,
            'titreOnglet' => $titreOnglet,
            'grosOeuvre' => $grosOeuvre,
            'refererIsAForm' => $refererIsAForm
        ));
    }
}

