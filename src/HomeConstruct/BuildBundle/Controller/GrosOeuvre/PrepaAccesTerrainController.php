<?php

namespace HomeConstruct\BuildBundle\Controller\GrosOeuvre;
use HomeConstruct\BuildBundle\Entity\GrosOeuvre;
use HomeConstruct\BuildBundle\Entity\PrepaAccesTerrain;
use HomeConstruct\BuildBundle\Entity\ElagageArbre;
use HomeConstruct\BuildBundle\Entity\Arbre;
use HomeConstruct\BuildBundle\Entity\TailleArbre;
use HomeConstruct\BuildBundle\Entity\Terrassement;
use HomeConstruct\BuildBundle\Form\PrepaAccesTerrainType;
use HomeConstruct\BuildBundle\Form\TerrassementType;
use HomeConstruct\BuildBundle\Form\ArbreType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use HomeConstruct\BuildBundle\Entity\Projet;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * PrepaAccesTerrain controller.
 *
 * @Route("/gros-oeuvre/prepa-acces-terrain")
 */
class PrepaAccesTerrainController extends Controller{
    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }
    /**
     * @Route("/creation/{idGrosOeuvre}", name="home_construct_prepa_acces_terrain_creation")
     * @ParamConverter("grosOeuvre", options={"mapping": {"idGrosOeuvre": "id"}})
     * @Route("/edit/{idPrepaAcces}", name="home_construct_prepa_acces_terrain_modification")
     * @ParamConverter("prepaAccesTerrain", options={"mapping": {"idPrepaAcces": "id"}})
     * @Route("/edit/arbre/{id}", name="home_construct_arbre_modification")
     * @ParamConverter("arbres", options={"mapping": {"idArbre": "id"}})
     * @Route("/edit/terrassement/{idTerrassement}", name="home_construct_terrassement_modification")
     * @ParamConverter("terrassement", options={"mapping": {"idTerrassement": "id"}})
     */
    public function formAction(Request $request,PrepaAccesTerrain $prepaAccesTerrain=null, GrosOeuvre $grosOeuvre=null, Arbre $arbre=null, Terrassement $terrassement=null)
    {
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));
        if($prepaAccesTerrain or $arbre or $terrassement){
            $page = array(
                'title' => 'Modification',
                'sub_title' => 'Modification des infos sur la preparation d\'accès au terrain'
            );
            $titreOnglet = "Modification Préparation Acces Terrain";
            if($arbre){
                $prepaAccesTerrain = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('HomeConstructBuildBundle:PrepaAccesTerrain')
                    ->findOneBy(['elagageArbre' => $arbre->getElagageArbre()]);
                $formArbre = $this->createForm(ArbreType::class, $arbre, [
                    'valeurDessouchage'=>$arbre->getPrixDessouchage(),
                    'valeurAbattageNettoyage'=>$arbre->getPrixAbattageNettoyage(),
                ]);
            }else{
                $arbre = new Arbre();
                $formArbre =  $this->createForm(ArbreType::class, $arbre, [
                    'valeurDessouchage'=>0,
                    'valeurAbattageNettoyage'=>0,
                ]);
            }
            if($terrassement){
                $prepaAccesTerrain = $terrassement->getPrepaAccesTerrain();
                $formTerrassement = $this->createForm(TerrassementType::class);
            }else{
                $terrassement = new Terrassement();
                $formTerrassement = $this->createForm(TerrassementType::class, $terrassement);
            }
            $grosOeuvre = $prepaAccesTerrain->getGrosOeuvre();
        }else{
            $page = array(
                'title' => 'Création',
                'sub_title' => 'Ajout d\'infos sur la preparation d\'accès au terrain'
            );
            $titreOnglet = "Création Préparation Acces Terrain";
            $prepaAccesTerrain = new PrepaAccesTerrain();
            $formArbre = $this->createForm(ArbreType::class, (new Arbre()),  [
                'valeurDessouchage'=>0,
                'valeurAbattageNettoyage'=>0,
            ]);
            $formTerrassement = $this->createForm(TerrassementType::class, (new Terrassement()));
        }
        $tailles = $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TailleArbre')
            ->createQueryBuilder('ta')
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        $types = $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeArbre')
            ->createQueryBuilder('ty')
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        $travaux = $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:Travaux')
            ->createQueryBuilder('tr')
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        $evacuation = $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:EvacuationTerrassement')
            ->createQueryBuilder('ev')
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        $tarifs = $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TarifArbre')
            ->createQueryBuilder('ta')
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        $form = $this->createForm(PrepaAccesTerrainType::class, $prepaAccesTerrain);
        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $prixAvantCalculGO=$grosOeuvre->getPrix();
            $em = $this->getDoctrine()->getManager();
            if(!$prepaAccesTerrain->getId()){
                $creation=true;
                $grosOeuvre->setPrepaAccesTerrain($prepaAccesTerrain);
                $prepaAccesTerrain->setGrosOeuvre($grosOeuvre);
                $elagageArbre = new ElagageArbre();
                $prepaAccesTerrain->setElagageArbre($elagageArbre);
                $notif="Informations sur la préparation de l'accès au terrain ajoutées";
                $prepaAccesTerrain->setCreateur($this->getUser());
            }else{
                $creation=false;
                $notif="Informations sur la préparation de l'accès au terrain modifiées";
                $prepaAccesTerrain->setModifieur($this->getUser());
            }
            $prixAcces = $grosOeuvre->getInformationBase()->getSurfaceTotale() * 20;
            $prepaAccesTerrain->setPrixAcces($prixAcces);
            $prepaAccesTerrain->calculPrix();
            $em->persist($prepaAccesTerrain->getElagageArbre());
            $em->persist($prepaAccesTerrain);
            $em->flush();
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
            // fonction qui envoie des mails de notif aux clients du projet
            $pathHelper->sendMailToClientsWhenEdit(
                $this->get('symracine_mail.mailer'),
                $this->getUser(),
                $grosOeuvre,
                $prixAvantCalculGO,
                $prixApresCalculGO,
                $creation,
                'HomeConstructBuildBundle:Mail:prepaAccesTerrain_edited'
            );
            return $this->redirectToRoute('home_construct_prepa_acces_terrain_modification', [
                'idPrepaAcces' => $prepaAccesTerrain->getId()
            ]);
        }elseif($request->isMethod('POST') and $formArbre->handleRequest($request)->isValid()){
            $prixAvantCalculGO=$grosOeuvre->getPrix();
            $em = $this->getDoctrine()->getManager();
            $elagageArbre = $prepaAccesTerrain->getElagageArbre();
            if(!$arbre->getId()){
                $arbre->setElagageArbre($elagageArbre);
                $arbreExiste = $em->getRepository('HomeConstructBuildBundle:Arbre')
                    ->findOneBy(['tailleArbre' => $arbre->getTailleArbre(), 'typeArbre' => $arbre->getTypeArbre()]);
                if($arbreExiste){
                    $arbre->setPrixAbattageNettoyage($arbre->getPrixAbattageNettoyage());
                    $arbre->setPrixDessouchage($arbre->getPrixDessouchage());
                    $arbre->setQuantite( ($arbre->getQuantite()+$arbreExiste->getQuantite()));
                    $em->remove($arbreExiste);
                }
                $notif="Informations sur l'arbre ajoutées";
            }else{
                $prepaAccesTerrain->setModifieur($this->getUser());
                $notif="Informations sur l'arbre modifiées";
            }
            $tarifArbre = $em->getRepository('HomeConstructBuildBundle:TarifArbre')
                ->findOneBy(['nombreArbre' => $arbre->getQuantite()]);
            if(!$tarifArbre){
                $prixTarifArbre = 390 + (($arbre->getQuantite() - 5) * 60);
            }else{
                $prixTarifArbre = $tarifArbre->getPrix();
            }
            $arbre->setTarifArbre($prixTarifArbre);
            $em->persist($arbre);
            $em->persist($elagageArbre);
            $em->flush();
            $prepaAccesTerrain->calculPrix();
            $em->persist($prepaAccesTerrain);
            $em->flush();
            $em->persist($grosOeuvre);
            $em->flush();
            $projet=$grosOeuvre->getProjet();
            $em->persist($projet);
            $em->flush();
            $prixApresCalculGO=$grosOeuvre->getPrix();
            $pathHelper->showNotifPriceGrosOeuvre($prixAvantCalculGO,$prixApresCalculGO);
            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            // fonction qui envoie des mails de notif aux clients du projet
            $pathHelper->sendMailToClientsWhenEdit(
                $this->get('symracine_mail.mailer'),
                $this->getUser(),
                $grosOeuvre,
                $prixAvantCalculGO,
                $prixApresCalculGO,
                false,
                'HomeConstructBuildBundle:Mail:prepaAccesTerrain_edited'
            );
            return $this->redirectToRoute('home_construct_prepa_acces_terrain_modification', [
                'idPrepaAcces' => $prepaAccesTerrain->getId()
            ]);
        }elseif($request->isMethod('POST') and $formTerrassement->handleRequest($request)->isValid()){
            $prixAvantCalculGO=$grosOeuvre->getPrix();
            $em = $this->getDoctrine()->getManager();
            if(!$terrassement->getId()){
                $terrassement->setPrepaAccesTerrain($prepaAccesTerrain);
                $notif="Informations sur le terrassement ajoutées";
                $prepaAccesTerrain->setModifieur($this->getUser());
            }else{
                $notif="Informations sur le terrassement modifiées";
            }
            $em->persist($terrassement);
            $em->flush();
            $prepaAccesTerrain->calculPrix();
            $em->persist($prepaAccesTerrain);
            $em->flush();
            $em->persist($grosOeuvre);
            $em->flush();
            $projet=$grosOeuvre->getProjet();
            $em->persist($projet);
            $em->flush();
            $prixApresCalculGO=$grosOeuvre->getPrix();
            $pathHelper->showNotifPriceGrosOeuvre($prixAvantCalculGO,$prixApresCalculGO);
            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            // fonction qui envoie des mails de notif aux clients du projet
            $pathHelper->sendMailToClientsWhenEdit(
                $this->get('symracine_mail.mailer'),
                $this->getUser(),
                $grosOeuvre,
                $prixAvantCalculGO,
                $prixApresCalculGO,
                false,
                'HomeConstructBuildBundle:Mail:prepaAccesTerrain_edited'
            );
            return $this->redirectToRoute('home_construct_prepa_acces_terrain_modification', [
                'idPrepaAcces' => $prepaAccesTerrain->getId(),
            ]);
        }
        return $this->render('@HomeConstructBuild/PrepaAccesTerrain/form.html.twig', [
            'form' => $form->createView(),
            'formArbre' => $formArbre->createView(),
            'formTerrassement' => $formTerrassement->createView(),
            'prepaAccesTerrain' => $prepaAccesTerrain,
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'grosOeuvre' => $grosOeuvre,
            'tailles' => $tailles,
            'types' => $types,
            'travaux' => $travaux,
            'evacuation' => $evacuation,
            'tarifs' => $tarifs,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     *  @Route("/delete/arbre/{id}", name="home_construct_arbre_delete")
     *  @ParamConverter("arbres", options={"mapping": {"idArbre": "id"}})
     */
    public function deleteArbreAction(Request $request, Arbre $arbre){
        $elagageArbre = $arbre->getElagageArbre();
        $prepaAccesTerrain = $this->getDoctrine()
            ->getManager()
            ->getRepository('HomeConstructBuildBundle:PrepaAccesTerrain')
            ->findOneBy(['elagageArbre' => $elagageArbre]);
        $grosOeuvre = $this->getDoctrine()
            ->getManager()
            ->getRepository('HomeConstructBuildBundle:GrosOeuvre')
            ->findOneBy(['prepaAccesTerrain' => $prepaAccesTerrain]);
        $em = $this->getDoctrine()->getManager();
        $em->remove($arbre);
        $em->persist($prepaAccesTerrain);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', "Arbre supprimé");
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_prepa_acces_terrain_modification', ['idPrepaAcces' => $prepaAccesTerrain->getId()]);
    }
    /**
     * @Route("/delete/Terrassement/{idTerrassement}", name="home_construct_terrassement_delete")
     * @ParamConverter("terrassement", options={"mapping": {"idTerrassement": "id"}})
     */
    public function deleteTerrassementAction(Request $request, Terrassement $terrassement){
        $prepaAccesTerrain = $terrassement->getPrepaAccesTerrain();
        $grosOeuvre = $this->getDoctrine()
            ->getManager()
            ->getRepository('HomeConstructBuildBundle:GrosOeuvre')
            ->findOneBy(['prepaAccesTerrain' => $prepaAccesTerrain]);
        $em = $this->getDoctrine()->getManager();
        $em->remove($terrassement);
        $em->persist($prepaAccesTerrain);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', "Terrassement supprimé");
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_prepa_acces_terrain_modification', ['idPrepaAcces' => $prepaAccesTerrain->getId()]);
    }
    /**
     * @Route("/profile/view/{id}", name="home_construct_prepa_acces_terrain_profile")
     */
    public function profileAction(Request $request, PrepaAccesTerrain $prepaAccesTerrain)
    {
        $grosOeuvre=$this->getDoctrine()
            ->getManager()
            ->getRepository('HomeConstructBuildBundle:GrosOeuvre')
            ->findOneBy(['prepaAccesTerrain'=>$prepaAccesTerrain]);

        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        $elagageArbre = $prepaAccesTerrain->getElagageArbre();
        $arbres = $elagageArbre->getArbres();
        $terrassements = $prepaAccesTerrain->getTerrassements();
        $page = array(
            'title' => 'Gros Oeuvre',
            'sub_title' => 'Infos sur la preparation du terrain'
        );
        $titreOnglet = "Preparation du terrain";
        $object = 'Preparation du terrain';
        return $this->render('@HomeConstructBuild/PrepaAccesTerrain/profile.html.twig', array(
            'page' => $page,
            'object' => $object,
            'prepaAccesTerrain' => $prepaAccesTerrain,
            'elagageArbre' => $elagageArbre,
            'arbres' => $arbres,
            'terrassements' => $terrassements,
            'grosOeuvre' => $grosOeuvre,
            'titreOnglet' => $titreOnglet,
            'refererIsAForm' => $refererIsAForm
        ));
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_prepa_acces_terrain_delete")
     */
    public function deleteAction(Request $request, PrepaAccesTerrain $prepaAccesTerrain)
    {
        $em = $this->getDoctrine()->getManager();
        $grosOeuvre=$em->getRepository('HomeConstructBuildBundle:GrosOeuvre')->findOneBy(['prepaAccesTerrain'=>$prepaAccesTerrain]);
        $em->remove($prepaAccesTerrain);
        $em->flush();
        $request->getSession()->set('entity-just-deleted','prepa-acces-terrain');
        $request->getSession()->getFlashBag()->add('notice', "Informations sur la préparation d\'accès au terrain supprimées");
        $request->getSession()->getFlashBag()->add('notif', 'True');
        $pathHelper=new PathHelper($em,$request);
        $pathHelper->sendMailToClientsWhenDelete(
            $this->get('symracine_mail.mailer'),
            $this->getUser(),
            $grosOeuvre,
            'HomeConstructBuildBundle:Mail:prepaAccesTerrain_deleted'
        );
        return $this->redirectToRoute('home_construct_gros_oeuvre_profile', [
            'id' => $grosOeuvre->getId()
        ]);
    }
}
