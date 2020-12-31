<?php

namespace HomeConstruct\BuildBundle\Controller\GrosOeuvre;

use HomeConstruct\BuildBundle\Entity\GrosOeuvre;
use HomeConstruct\BuildBundle\Entity\Piece;
use HomeConstruct\BuildBundle\Entity\TypeMateriauxMur;
use HomeConstruct\BuildBundle\Form\AppuisFenetreType;
use HomeConstruct\BuildBundle\Form\ElevationType;
use HomeConstruct\BuildBundle\Form\MurPieceType;
use HomeConstruct\BuildBundle\Form\MurType;
use HomeConstruct\BuildBundle\Form\PoutreType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use HomeConstruct\BuildBundle\Entity\Elevation;
use HomeConstruct\BuildBundle\Entity\Mur;
use HomeConstruct\BuildBundle\Entity\Poutre;
use HomeConstruct\BuildBundle\Entity\AppuisFenetre;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Elevation controller.
 *
 * @Route("/gros-oeuvre/elevation")
 */
class ElevationController extends Controller{
    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }
    /**
     * @Route("/creation/{idGrosOeuvre}", name="home_construct_elevation_creation")
     * @ParamConverter("grosOeuvre", options={"mapping": {"idGrosOeuvre": "id"}})
     * @Route("/edit/{idElevation}", name="home_construct_elevation_modification")
     * @ParamConverter("elevation", options={"mapping": {"idElevation": "id"}})
     * @Route("/edit/mur/{idMur}", name="home_construct_mur_modification")
     * @ParamConverter("mur", options={"mapping": {"idMur": "id"}})
     * @Route("/edit/poutre/{idPoutre}", name="home_construct_poutre_modification")
     * @ParamConverter("poutre", options={"mapping": {"idPoutre": "id"}})
     * @Route("/edit/appuisFenetre/{idAppuisFenetre}", name="home_construct_appuis_fenetre_modification")
     * @ParamConverter("appuisFenetre", options={"mapping": {"idAppuisFenetres": "id"}})
     */
    public function formAction(Request $request,Elevation $elevation=null, GrosOeuvre $grosOeuvre=null, Mur $mur=null, Poutre $poutre=null, AppuisFenetre $appuisFenetre=null)
    {
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));
        if($elevation or $mur or $poutre or $appuisFenetre){
            $page = array(
                'title' => 'Modification',
                'sub_title' => 'Modification d\'une élévation'
            );
            $em = $this->getDoctrine()->getManager();
            if($elevation) {
                $grosOeuvre = $elevation->getGrosOeuvre();
            }
            if($mur) {
                $grosOeuvre = $mur->getElevation()->getGrosOeuvre();
                $elevation = $mur->getElevation();
            }
            if($poutre) {
                $grosOeuvre = $poutre->getElevation()->getGrosOeuvre();
                $elevation = $poutre->getElevation();
            }
            if($appuisFenetre) {
                $grosOeuvre = $appuisFenetre->getElevation()->getGrosOeuvre();
                $elevation = $appuisFenetre->getElevation();
            }
            $nbPiece=$grosOeuvre->getInformationBase()->getNbPieces();
            $pieces=$grosOeuvre->getPieces();
            $murs=$elevation->getMur();
            /*    foreach ($murs as $mur){
                    for($i=0; $i<$nbPiece; $i++){
                        if ( $mur->getNom()=='MurPiece'.$pieces[$i]->getId() ){
                            $mur->setLongueur($pieces[$i]->getSurface()*4);

                        }
                        else if($mur->getNom()=="MurMaison"){
                            $mur->setLongueur($grosOeuvre->getInformationBase()->getSurfaceTotale()*4);
                        }
                        $em->persist($mur);
                    }
                } */
            $titreOnglet = "Modification Elevation";
            if($mur){
                $elevation = $mur->getElevation();


                if($mur->getNom()=="MurMaison" || $mur->getPiece()!=null){
                    $formMur = $this->createForm(MurPieceType::class, $mur);
                }
                else{
                    $formMur = $this->createForm(MurType::class, $mur);
                }

            }else{
                $mur = new Mur();
                $formMur = $this->createForm(MurType::class, $mur);
            }
            if($poutre){
                $elevation = $poutre->getElevation();
                $formPoutre = $this->createForm(PoutreType::class, $poutre);
            }else{
                $poutre = new Poutre();
                $formPoutre = $this->createForm(PoutreType::class, $poutre);
            }
            if($appuisFenetre){
                $elevation = $appuisFenetre->getElevation();
                $formAppuisFenetre = $this->createForm(AppuisFenetreType::class, $appuisFenetre);
            }else{
                $appuisFenetre = new AppuisFenetre();
                $formAppuisFenetre = $this->createForm(AppuisFenetreType::class, $appuisFenetre);
            }
            $grosOeuvre = $elevation->getGrosOeuvre();


        }else{
            $page = array(
                'title' => 'Création',
                'sub_title' => 'Création d\'une élévation'
            );
            $titreOnglet = "Création Elevation";
            $elevation = new Elevation();
            $formMur = $this->createForm(MurType::class, (new Mur()));
            $formPoutre = $this->createForm(PoutreType::class, (new Poutre()));
            $formAppuisFenetre = $this->createForm(AppuisFenetreType::class, (new AppuisFenetre()));

            $nbPiece=$grosOeuvre->getInformationBase()->getNbPieces();
            $pieces=$grosOeuvre->getPieces();
            $em = $this->getDoctrine()->getManager();
            for($i=0; $i<$nbPiece; $i++){
                $m=new Mur();
                $m->setElevation($elevation);
                $m->setHauteur(0);
                $m->setLongueur($pieces[$i]->getSurface()*4);
                $m->setHauteur(2);
                $m->setNom('MurPiece'.strval($i+1));
                $m->setPiece($pieces[$i]);
                $type=$this->getDoctrine()->getManager()->getRepository('HomeConstructBuildBundle:TypeMateriauxMur')->findOneBy(['nom' => 'Parpaing']);
                if($type==null){
                    $type=new TypeMateriauxMur();
                }
                $m->setType($type);
                $pieces[$i]->setMur($m);
                $elevation->addMur($m);
                $em->persist($pieces[$i]);

            }
            $m=new Mur();
            $m->setElevation($elevation);
            $m->setHauteur(0);
            $m->setLongueur($grosOeuvre->getInformationBase()->getSurfaceTotale()*4);
            $m->setHauteur(2);
            $m->setNom('MurMaison');
            $type=$this->getDoctrine()->getManager()->getRepository('HomeConstructBuildBundle:TypeMateriauxMur')->findOneBy(['nom' => 'Parpaing']);
            if($type==null){
                $type=new TypeMateriauxMur();
            }
            $m->setType($type);
            $elevation->addMur($m);
        }
        $typeMur = $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeMateriauxMur')
            ->createQueryBuilder('ta')
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        $typePoutre = $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeMateriauxPoutre')
            ->createQueryBuilder('ty')
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        $typeAppuisFenetre = $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeAppuisFenetre')
            ->createQueryBuilder('to')
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        $form = $this->createForm(ElevationType::class, $elevation);
        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $prixAvantCalculGO=$grosOeuvre->getPrix();
            $em = $this->getDoctrine()->getManager();
            if(!$elevation->getId()){
                $grosOeuvre->setElevation($elevation);
                $elevation->setGrosOeuvre($grosOeuvre);
                $notif="Informations sur l'élévation ajoutées";
            }else{
                $notif="Informations sur l'élévation modifiées";
            }
            $elevation->calculPrix();
            $em->persist($elevation);
            $em->persist($grosOeuvre);
            $em->flush();
            $projet=$grosOeuvre->getProjet();
            $projet->calculPrix();
            $em->persist($projet);
            $em->flush();
            $prixApresCalculGO=$grosOeuvre->getPrix();
            $pathHelper->showNotifPriceGrosOeuvre($prixAvantCalculGO,$prixApresCalculGO);
            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_elevation_modification', [
                'idElevation' => $elevation->getId()
            ]);
        }elseif($request->isMethod('POST') and $formMur->handleRequest($request)->isValid()){
            $prixAvantCalculGO=$grosOeuvre->getPrix();
            $em = $this->getDoctrine()->getManager();
            if(!$mur->getId()){
                $mur->setElevation($elevation);
                $notif="Informations sur le mur ajoutées";
                $mur->setNom(".".$mur->getNom());
            }else{
                $notif="Informations sur le mur modifiées";

                $piece=$mur->getPiece();
                if($piece != null){
                    $mur->setLongueur($piece->getSurface()*4);
                    $mur->setHauteur(2);
                }
                elseif($mur->getNom()=="MurMaison" and $elevation->getGrosOeuvre()->getInformationBase()){
                    $mur->setLongueur($elevation->getGrosOeuvre()->getInformationBase()->getSurfaceTotale()*4);
                    $mur->setHauteur(2);
                }
                else{
                    $mur->setLongueur(0);
                    $m->setHauteur(2);
                }
            }
            $em->persist($mur);
            $em->flush();
            $elevation->calculPrix();
            $em->persist($elevation);
            $em->persist($grosOeuvre);
            $em->flush();
            $prixApresCalculGO=$grosOeuvre->getPrix();
            $pathHelper->showNotifPriceGrosOeuvre($prixAvantCalculGO,$prixApresCalculGO);
            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_elevation_modification', [
                'idElevation' => $elevation->getId(),
            ]);
        }elseif($request->isMethod('POST') and $formPoutre->handleRequest($request)->isValid()){
            $prixAvantCalculGO=$grosOeuvre->getPrix();
            $em = $this->getDoctrine()->getManager();
            if(!$poutre->getId()){
                $poutre->setElevation($elevation);
                $notif="Informations sur la poutre ajoutées";
            }else{
                $notif="Informations sur la poutre modifiées";
            }
            $em->persist($poutre);
            $em->flush();
            $elevation->calculPrix();
            $em->persist($elevation);
            $em->persist($grosOeuvre);
            $em->flush();
            $prixApresCalculGO=$grosOeuvre->getPrix();
            $pathHelper->showNotifPriceGrosOeuvre($prixAvantCalculGO,$prixApresCalculGO);
            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_elevation_modification', [
                'idElevation' => $elevation->getId(),
            ]);
        }
        elseif($request->isMethod('POST') and $formAppuisFenetre->handleRequest($request)->isValid()){
            $prixAvantCalculGO=$grosOeuvre->getPrix();
            $em = $this->getDoctrine()->getManager();
            if(!$appuisFenetre->getId()){
                $appuisFenetre->setElevation($elevation);
                $notif="Informations sur l'appui de fenêtre ajoutées";
            }else{
                $notif="Informations sur l'appui de fenêtre modifiées";
            }
            $em->persist($appuisFenetre);
            $em->flush();
            $elevation->calculPrix();
            $em->persist($elevation);
            $em->persist($grosOeuvre);
            $em->flush();
            $prixApresCalculGO=$grosOeuvre->getPrix();
            $pathHelper->showNotifPriceGrosOeuvre($prixAvantCalculGO,$prixApresCalculGO);
            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_elevation_modification', [
                'idElevation' => $elevation->getId(),
            ]);
        }

        return $this->render('@HomeConstructBuild/Elevation/form.html.twig', [
            'form' => $form->createView(),
            'formMur' => $formMur->createView(),
            'formPoutre' => $formPoutre->createView(),
            'formAppuisFenetre' => $formAppuisFenetre->createView(),
            'elevation' => $elevation,
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'grosOeuvre' => $grosOeuvre,

            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     *  @Route("/suppression/poutre/{idPoutre}", name="home_construct_poutre_delete")
     *  @ParamConverter("poutre", options={"mapping": {"idPoutre": "id"}})
     */
    public function deletePoutreAction(Request $request, Poutre $poutre){
        $elevation=$poutre->getElevation();
        $grosOeuvre=$elevation->getGrosOeuvre();
        $prixAvantCalculGo=$grosOeuvre->getPrix();
        $em = $this->getDoctrine()->getManager();
        $em->remove($poutre);
        $em->persist($elevation);
        $em->flush();
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $pathHelper->calculPrixGoAndProjet($grosOeuvre);
        $prixApresCalculGo=$grosOeuvre->getPrix();
        $pathHelper->showNotifPriceGrosOeuvre($prixAvantCalculGo,$prixApresCalculGo);
        $request->getSession()->getFlashBag()->add('notice', "Poutre supprimée");
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_elevation_modification', ['idElevation' => $elevation->getId()]);
    }

    /**
     *  @Route("/suppression/mur/{idMur}", name="home_construct_mur_delete")
     *  @ParamConverter("mur", options={"mapping": {"idMur": "id"}})
     */
    public function deleteMurAction(Request $request, Mur $mur){
        $elevation = $mur->getElevation();
        $grosOeuvre = $elevation->getGrosOeuvre();
        $prixAvantCalculGo=$grosOeuvre->getPrix();
        $em = $this->getDoctrine()->getManager();

        if($mur->getPiece()==null and !($mur->getNom()!="MurMaison")) {
            $em->remove($mur);
            $request->getSession()->getFlashBag()->add('notice', "Mur supprimé");
        }
        else{
            $request->getSession()->getFlashBag()->add('notice', "Ce Mur ne peut pas être supprimé directement");
        }
        $em->persist($elevation);
        $em->flush();
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $pathHelper->calculPrixGoAndProjet($grosOeuvre);
        $prixApresCalculGo=$grosOeuvre->getPrix();
        $pathHelper->showNotifPriceGrosOeuvre($prixAvantCalculGo,$prixApresCalculGo);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_elevation_modification', ['idElevation' => $elevation->getId()]);
    }
    /**
     * @Route("/suppression/AppuisFenetre/{idAppuisFenetre}", name="home_construct_appuis_fenetre_delete")
     * @ParamConverter("appuisFenetre", options={"mapping": {"idAppuisFenetre": "id"}})
     */
    public function deleteAppuisFenetreAction(Request $request, AppuisFenetre $appuisFenetre){
        $elevation = $appuisFenetre->getElevation();
        $grosOeuvre = $elevation->getGrosOeuvre();
        $prixAvantCalculGo=$grosOeuvre->getPrix();
        $em = $this->getDoctrine()->getManager();
        $em->remove($appuisFenetre);
        $em->persist($elevation);
        $em->flush();
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $pathHelper->calculPrixGoAndProjet($grosOeuvre);
        $prixApresCalculGo=$grosOeuvre->getPrix();
        $pathHelper->showNotifPriceGrosOeuvre($prixAvantCalculGo,$prixApresCalculGo);
        $request->getSession()->getFlashBag()->add('notice', "Appui de fenêtre supprimé");
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_elevation_modification', ['idElevation' => $elevation->getId()]);
    }
    /**
     * @Route("/profile/view/{id}", name="home_construct_elevation_profile")
     */
    public function profileAction(Request $request, Elevation $elevation)
    {
        $grosOeuvre=$elevation->getGrosOeuvre();

        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=$this->container->get('homeconstruct_service.pathhelper');
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        $murs = $elevation->getMur();
        $appuisFenetres = $elevation->getAppuisFenetre();
        $poutres = $elevation->getPoutre();
        $page = array(
            'title' => 'Gros Oeuvre',
            'sub_title' => 'Infos sur l\'élevation'
        );
        $titreOnglet = "Preparation de elevation";
        $object = 'Preparation de l\'élévation';
        $i=0;
        $em = $this->getDoctrine()->getManager();
        $b=false;
        foreach ($elevation->getMur() as $m){
            if($m->getNom()=="MurMaison"){
                $b=true;
                if($elevation->getGrosOeuvre()->getInformationBase()){
                    $m->setLongueur($elevation->getGrosOeuvre()->getInformationBase()->getSurfaceTotale()*4);
                    $m->setHauteur(2);
                }
                else{
                    $m->setLongueur(0);
                    $m->setHauteur(2);
                }
                $em->persist($m);
            }
        }
        if(!$b){
            $m=new Mur();
            $m->setElevation($elevation);
            $m->setHauteur(0);
            if($elevation->getGrosOeuvre()->getInformationBase()){
                $m->setLongueur($elevation->getGrosOeuvre()->getInformationBase()->getSurfaceTotale()*4);
                $m->setHauteur(2);
            }
            else{
                $m->setLongueur(0);
                $m->setHauteur(2);
            }
            $m->setNom('MurMaison');
            $type=$this->getDoctrine()->getManager()->getRepository('HomeConstructBuildBundle:TypeMateriauxMur')->findOneBy(['nom' => 'Parpaing']);
            if($type==null){
                $type=new TypeMateriauxMur();
            }
            $m->setType($type);
            $i=$i+1;
            $elevation->addMur($m);
            $em->persist($m);
        }
        foreach ($elevation->getGrosOeuvre()->getPieces() as $p){
            if(!$p->getMur()){
                $m=new Mur();
                $m->setElevation($elevation);
                $m->setHauteur(0);
                $m->setLongueur($p->getSurface()*4);
                $m->setHauteur(2);
                $m->setNom('MurPiece');
                $m->setPiece($p);
                $type=$this->getDoctrine()->getManager()->getRepository('HomeConstructBuildBundle:TypeMateriauxMur')->findOneBy(['nom' => 'Parpaing']);
                if($type==null){
                    $type=new TypeMateriauxMur();
                }
                $m->setType($type);
                $i=$i+1;
                $p->setMur($m);
                $elevation->addMur($m);
                $em->persist($p);
                $em->persist($m);
            }
        }
        $em->persist($elevation);
        $em->flush();
        return $this->render('@HomeConstructBuild/Elevation/profile.html.twig', array(
            'page' => $page,
            'object' => $object,
            'elevation' => $elevation,
            'murs' => $murs,
            'appuisFenetres' => $appuisFenetres,
            'poutres' => $poutres,
            'grosOeuvre' => $elevation->getGrosOeuvre(),
            'titreOnglet' => $titreOnglet,
            'refererIsAForm' => $refererIsAForm
        ));
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_elevation_delete")
     */
    public function deleteAction(Request $request, Elevation $elevation)
    {
        $em = $this->getDoctrine()->getManager();
        $grosOeuvre=$elevation->getGrosOeuvre();
        foreach ($elevation->getMur() as $mur){
            if($mur->getPiece()!=null) {
                $mur->getPiece()->setMur(null);
                $mur->setPiece(null);
            }
            $em->remove($mur);
        }
        $em->remove($elevation);
        $em->flush();
        $request->getSession()->set('entity-just-deleted','elevation');
        $request->getSession()->getFlashBag()->add('notice', "Informations sur l'élevation supprimées");
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_gros_oeuvre_profile', [
            'id' => $grosOeuvre->getId()
        ]);
    }
}

