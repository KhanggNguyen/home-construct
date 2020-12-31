<?php
namespace HomeConstruct\BuildBundle\Controller\GrosOeuvre;

use HomeConstruct\BuildBundle\Entity\Chauffage;
use HomeConstruct\BuildBundle\Entity\GrosOeuvre;
use HomeConstruct\BuildBundle\Entity\SecondOeuvre;
use HomeConstruct\BuildBundle\Form\PieceType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use HomeConstruct\BuildBundle\Entity\Piece;
use HomeConstruct\BuildBundle\Entity\Mur;
use HomeConstruct\BuildBundle\Entity\TypeMateriauxMur;
use HomeConstruct\BuildBundle\Entity\Elevation;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
/**
 * Piece controller.
 *
 * @Route("/gros-oeuvre/piece")
 */
class PieceController extends Controller
{
    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/{idGrosOeuvre}", name="home_construct_piece_creation")
     * @ParamConverter("grosOeuvre", options={"mapping": {"idGrosOeuvre": "id"}})
     * @Route("/edit/{idPiece}", name="home_construct_piece_edit")
     * @ParamConverter("piece", options={"mapping": {"idPiece": "id"}})
     */
    public function formAction(Request $request,Piece $piece=null,GrosOeuvre $grosOeuvre=null)
    {
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        if(!$piece){
            $page = array(
                'title' => 'Gros Oeuvre',
                'sub_title' => "Ajout d'une pièce"
            );
            $titreOnglet = "Modif Gros Oeuvre";
            $piece = new Piece();
        }else{
            $page = array(
                'title' => 'Gros Oeuvre',
                'sub_title' => "Modification d'une pièce"
            );
            $titreOnglet = "Modif Gros Oeuvre";
            $grosOeuvre=$piece->getGrosOeuvre();
        }
        $form = $this->createForm(PieceType::class, $piece,['piece'=>$piece]);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!$piece->getId()){
                $infoBase=$grosOeuvre->getInformationBase();
                $infoBase->setNbPieces($infoBase->getNbPieces()+1);
                $em->persist($infoBase);
                $piece->setGrosOeuvre($grosOeuvre);
                if($infoBase->getNbPieces()==1){
                    $request->getSession()->getFlashBag()->add('notice', $infoBase->getNbPieces()."ère pièce ajoutée");
                }else{
                    $request->getSession()->getFlashBag()->add('notice', $infoBase->getNbPieces()."ème pièce ajoutée");
                }

                $request->getSession()->getFlashBag()->add('notif', 'True');

                $elevation=$grosOeuvre->getElevation();
                if($elevation==null){
                    $elevation=new Elevation();
                    $elevation->setGrosOeuvre($grosOeuvre);
                    $grosOeuvre->setElevation($elevation);
                }
                $murNouveau=new Mur();
                $murNouveau->setHauteur(0);
                $murNouveau->setLongueur($piece->getSurface()*4);
                $murNouveau->setHauteur(2);
                $type=$this->getDoctrine()->getManager()->getRepository('HomeConstructBuildBundle:TypeMateriauxMur')->findOneBy(['nom' => 'Parpaing']);
                if($type==null){
                    $type=new TypeMateriauxMur();
                }
                $murNouveau->setType($type);
                $murNouveau->setElevation($elevation);
                $murNouveau->setPiece($piece);
                $piece->setMur($murNouveau);
                $elevation->addMur($murNouveau);
            }else{
                $request->getSession()->getFlashBag()->add('notice', "Pièce modifiée");
                $request->getSession()->getFlashBag()->add('notif', 'True');
                $elevation=$grosOeuvre->getElevation();
                if($elevation==null){
                    $elevation=new Elevation();
                    $elevation->setGrosOeuvre($grosOeuvre);
                    $grosOeuvre->setElevation($elevation);
                }
                if($piece->getMur()!=null){
                    $piece->getMur()->setLongueur($piece->getSurface()*4);
                }
            }
            $chauffage=$piece->getChauffage();
            $ventilation=$piece->getVentilation();
            $climatisation=$piece->getClimatisation();
            $revetementSol=$piece->getRevetementSol();
            $projet=$piece->getGrosOeuvre()->getProjet();

            // si le projet ne possède pas encore de second oeuvre on en cree un pour pouvoir creer des chauffages, clim..
            if(!($projet->getSecondOeuvre())){
                $secondOeuvre=new SecondOeuvre();
                $secondOeuvre->setProjet($piece->getGrosOeuvre()->getProjet());
                $projet->setSecondOeuvre($secondOeuvre);
                $em->persist($secondOeuvre);
                $em->persist($projet);
            }else{
                $secondOeuvre=$projet->getSecondOeuvre();
            }
            $prixAvantCalculSO=$secondOeuvre->getPrix();

            if($chauffage){
                if($chauffage->getType()!=null) {
                    $chauffage->setEm($em);
                    $chauffage->setSecondOeuvre($secondOeuvre);
                    $chauffage->setPiece($piece);
                    $em->persist($chauffage);
                }else{
                    $piece->setChauffage(null);
                }
            }
            if($ventilation) {
                if ($ventilation->getType() != null) {
                    $ventilation->setEm($em);
                    $ventilation->setSecondOeuvre($secondOeuvre);
                    $ventilation->setPiece($piece);
                    $em->persist($ventilation);
                }else{
                    $piece->setVentilation(null);
                }
            }
            if($climatisation) {
                if ($climatisation->getType() != null) {
                    $climatisation->setEm($em);
                    $climatisation->setSecondOeuvre($secondOeuvre);
                    $climatisation->setPiece($piece);
                    $em->persist($climatisation);
                }else{
                    $piece->setClimatisation(null);
                }
            }
            if($revetementSol) {
                if ($revetementSol->getType() != null) {
                    $revetementSol->setEm($em);
                    $revetementSol->setSecondOeuvre($secondOeuvre);
                    $revetementSol->setPiece($piece);
                    $em->persist($revetementSol);
                }else{
                    $piece->setRevetementSol(null);
                }
            }
            $em->persist($piece);
            $em->persist($elevation);
            $em->persist($grosOeuvre);
            $em->flush();
            if(isset($murNouveau)){
                $murNouveau->setNom('MurPiece');
                $em->persist($murNouveau);
            }
            $em->flush();
            $prixApresCalculSO=$secondOeuvre->getPrix();
            $pathHelper->showNotifPriceSecondOeuvre($prixAvantCalculSO,$prixApresCalculSO);
            return $this->redirectToRoute('home_construct_piece_profile',[
                'idGrosOeuvre'=>$piece->getGrosOeuvre()->getId()
            ]);
        };

        return $this->render('@HomeConstructBuild/Piece/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'editMode' => $piece->getId() !== null,
            'refererIsAForm'=>$refererIsAForm,
            'grosOeuvre' => $grosOeuvre,
        ]);
    }

    /**
     * @Route("/profile/view/{idGrosOeuvre}", name="home_construct_piece_profile")
     * @ParamConverter("grosOeuvre", options={"mapping": {"idGrosOeuvre": "id"}})
     */
    public function profileAction(Request $request, GrosOeuvre $grosOeuvre)
    {
        $pieces=$this->getDoctrine()
            ->getManager()
            ->getRepository('HomeConstructBuildBundle:Piece')
            ->findBy(['grosOeuvre'=>$grosOeuvre]);
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=$this->container->get('homeconstruct_service.pathhelper');
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));
        $refererIsListEntityLinked=$pathHelper->pathIsEntityLinkedPiece($request->headers->get('referer'));

        $page = array(
            'title' => 'Gros Oeuvre',
            'sub_title' => 'Infos sur les pièces'
        );
        $titreOnglet = "Pièces";

        $object = 'Piece';

        return $this->render('@HomeConstructBuild/Piece/profile.html.twig', array(
            'page' => $page,
            'object' => $object,
            'pieces' => $pieces,
            'titreOnglet' => $titreOnglet,
            'grosOeuvre' => $grosOeuvre,
            'refererIsAForm'=> $refererIsAForm,
            'refererLinked'=>$refererIsListEntityLinked
        ));
    }

    /**
     * @Route("/suppression/{idPiece}", name="home_construct_piece_delete")
     * @ParamConverter("piece", options={"mapping": {"idPiece": "id"}})
     */
    public function deleteAction(Request $request,Piece $piece)
    {
        /*$projet = $this->getDoctrine()->getManager()
                ->getRepository('HomeConstructBuildBundle:Projet')
                ->find($id);*/
        $grosOeuvre=$piece->getGrosOeuvre();
        $infoBase=$grosOeuvre->getInformationBase();
        $infoBase->setNbPieces($infoBase->getNbPieces()-1);

        $elevation=$grosOeuvre->getElevation();
        $em = $this->getDoctrine()->getManager();
        if($piece->getMur()!=null) {
            $em->remove($piece->getMur());
        }


        $em->persist($infoBase);
        $em->remove($piece);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'Pièce supprimé');
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_piece_profile', [
            'idGrosOeuvre' => $grosOeuvre->getId()
        ]);
    }
}