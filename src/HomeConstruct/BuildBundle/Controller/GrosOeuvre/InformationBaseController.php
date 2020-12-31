<?php

namespace HomeConstruct\BuildBundle\Controller\GrosOeuvre;

use HomeConstruct\BuildBundle\Entity\Elevation;
use HomeConstruct\BuildBundle\Entity\GrosOeuvre;
use HomeConstruct\BuildBundle\Entity\Mur;
use HomeConstruct\BuildBundle\Entity\Piece;
use HomeConstruct\BuildBundle\Form\InformationBaseCreationType;
use HomeConstruct\BuildBundle\Form\InformationBaseType;
use HomeConstruct\BuildBundle\Form\InformationBaseBisType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use HomeConstruct\BuildBundle\Entity\InformationBase;
use HomeConstruct\BuildBundle\Entity\TypeMateriauxMur;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * InformationBase controller.
 *
 * @Route("/gros-oeuvre/information-de-base")
 */
class InformationBaseController extends Controller
{


    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/{idGrosOeuvre}", name="home_construct_information_base_creation")
     * @ParamConverter("grosOeuvre", options={"mapping": {"idGrosOeuvre": "id"}})
     * @Route("/edit/{idInformationBase}", name="home_construct_information_base_edit")
     * @ParamConverter("informationBase", options={"mapping": {"idInformationBase": "id"}})
     */
    public function formAction(Request $request,InformationBase $informationBase=null,GrosOeuvre $grosOeuvre=null)
    {
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=$this->container->get('homeconstruct_service.pathhelper');
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        if(!$informationBase){
            $page = array(
                'title' => 'Gros Oeuvre',
                'sub_title' => 'Ajout des informations de base'
            );
            $titreOnglet = "Modif Gros Oeuvre";
            $informationBase = new InformationBase();
            $form = $this->createForm(InformationBaseType::class, $informationBase,[
                'valueSousSol'=> $informationBase->getSousSol(),
                'valueComble'=>$informationBase->getComble()
            ]);
        }else{
            $page = array(
                'title' => 'Gros Oeuvre',
                'sub_title' => 'Modification des informations de base'
            );

            $titreOnglet = "Modif Gros Oeuvre";
            $grosOeuvre = $this->getDoctrine()
                ->getManager()
                ->getRepository('HomeConstructBuildBundle:GrosOeuvre')
                ->findOneBy(['informationBase'=>$informationBase]);
            $nbPiecesRenseigne = $grosOeuvre->getInformationBase()->getNbPieces();
            $form = $this->createForm(InformationBaseBisType::class, $informationBase,[
                'valueSousSol'=> $informationBase->getSousSol(),
                'valueComble'=>$informationBase->getComble()
            ]);
        }


        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            if(!$informationBase->getId()){
                $nbPieces=$grosOeuvre->getInformationBase()->getNbPieces();
                // on crée le nb de pieces renseigné

                for ($i=0;$i<$nbPieces;$i++){
                    $piece = new Piece();
                    $piece->setGrosOeuvre($grosOeuvre);
                    $em=$this->getDoctrine()->getManager();
                    $em->persist($piece);
                }
                $informationBase->setGrosOeuvre($grosOeuvre);
                $notif="Gros oeuvre ajouté";
            }else{
                $em = $this->getDoctrine()->getManager();
                // si le nouveau nb de pieces est superieur à l'ancien on rajoute les nouvelles pieces
                if($grosOeuvre->getInformationBase()->getNbPieces()>$nbPiecesRenseigne){
                    $nbPiecesDePlus=$grosOeuvre->getInformationBase()->getNbPieces()-$nbPiecesRenseigne;
                    for ($i=0;$i<$nbPiecesDePlus;$i++){
                        $piece=new Piece();
                        $piece->setGrosOeuvre($grosOeuvre);
                        $em->persist($piece);
                    }
                    // si le nouveau nb de pieces est inferieur à l'ancien on supprime toutes les anciennes pieces
                    // et on crée les nouvelles
                }else if($grosOeuvre->getInformationBase()->getNbPieces()<$nbPiecesRenseigne){
                    $piecesDuGrosProjet = $this->getDoctrine()
                        ->getManager()
                        ->getRepository('HomeConstructBuildBundle:Piece')
                        ->findBy(['grosOeuvre'=>$grosOeuvre]);
                    foreach ($piecesDuGrosProjet as $pieceDuGrosProjet){
                        $em->remove($pieceDuGrosProjet);
                        $elevation=$grosOeuvre->getElevation();
                        $murs=$elevation->getMur();
                        foreach ($murs as $m){
                            if($m->getNom()=='MurPiece'.$pieceDuGrosProjet->getId()){
                                $elevation->removeMur($m);
                                $em->persist($m);
                                $em->persist($elevation);
                            }
                        }
                    }
                    for ($i=0;$i<$grosOeuvre->getInformationBase()->getNbPieces();$i++){
                        $piece=new Piece();
                        $piece->setGrosOeuvre($grosOeuvre);
                        $em->persist($piece);
                    }
                }
                $notif="Informations de base modifiées";
                $prixGOAvantCalcul=$grosOeuvre->getPrix();

                // on recupere le service PathHelper qui va nous permettre d'utiliser une fonction
                $pathHelper= new PathHelper($this->getDoctrine()->getManager(),$request);
                $tab=array(
                    $grosOeuvre->getEtudeSol(),
                    $grosOeuvre->getFondation()
                );
                // cette fonction permet de faire les appels nécessaires pour les calculs de prix
                // l'affichage des notifications est aussi géré
                $pathHelper->calculPrixAfterEditGo($tab,$grosOeuvre);

                $em->persist($grosOeuvre);
                // si le prix du gros oeuvre a été modifié
                $prixGOApresCalcul=$grosOeuvre->getPrix();
                if($prixGOAvantCalcul!=$prixGOApresCalcul){
                    $request->getSession()->getFlashBag()->add('newPrice3', 'Le prix total est passé de '.$prixGOAvantCalcul.'€ à '.$prixGOApresCalcul.'€');
                    $request->getSession()->getFlashBag()->add('nameEntity3', 'Gros Oeuvre');
                    $request->getSession()->getFlashBag()->add('calcul3', 'True');
                }
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($informationBase);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_information_base_profile',[
                'id'=>$informationBase->getId()
            ]);
        };

        return $this->render('@HomeConstructBuild/InformationBase/form.html.twig', [
            'form' => $form->createView(),
            'idGrosOeuvre' => $grosOeuvre->getId(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'editMode' => $informationBase->getId() !== null,
            'grosOeuvre' => $grosOeuvre,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }


    /**
     * @Route("/suppression/{id}", name="home_construct_information_base_delete")
     */
    public function deleteAction(Request $request, InformationBase $informationBase)
    {
        $grosOeuvre=$this->getDoctrine()
            ->getManager()
            ->getRepository('HomeConstructBuildBundle:GrosOeuvre')
            ->findOneBy(['informationBase'=>$informationBase]);
        $em = $this->getDoctrine()->getManager();
        $em->remove($informationBase);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', "Informations de base supprimées");
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_gros_oeuvre_profile', [
            'id'=>$grosOeuvre->getId()
        ]);
    }


    /**
     * @Route("/profile/view/{id}", name="home_construct_information_base_profile")
     */
    public function profileAction(Request $request, InformationBase $informationBase)
    {
        $grosOeuvre=$this->getDoctrine()
            ->getManager()
            ->getRepository('HomeConstructBuildBundle:GrosOeuvre')
            ->findOneBy(['informationBase'=>$informationBase]);

        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=$this->container->get('homeconstruct_service.pathhelper');
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        $page = array(
            'title' => 'Gros Oeuvre',
            'sub_title' => 'Informations de base'
        );
        $titreOnglet = "Informations de base";

        $object = 'Information Base';

        return $this->render('@HomeConstructBuild/InformationBase/profile.html.twig', array(
            'page' => $page,
            'object' => $object,
            'information' => $informationBase,
            'titreOnglet' => $titreOnglet,
            'grosOeuvre' => $grosOeuvre,
            'refererIsAForm' => $refererIsAForm
        ));
    }
}


