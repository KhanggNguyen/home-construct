<?php

namespace HomeConstruct\BuildBundle\Controller\GrosOeuvre;

use HomeConstruct\BuildBundle\Entity\GrosOeuvre;
use HomeConstruct\BuildBundle\Entity\Piece;
use HomeConstruct\BuildBundle\Form\CharpenteSansFermetteType;
use HomeConstruct\BuildBundle\Form\CharpenteType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use HomeConstruct\BuildBundle\Entity\Charpente;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Charpente controller.
 *
 * @Route("/gros-oeuvre/charpente")
 */
class CharpenteController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/{idGrosOeuvre}", name="home_construct_charpente_creation")
     * @ParamConverter("grosOeuvre", options={"mapping": {"idGrosOeuvre": "id"}})
     * @Route("/edit/{idCharpente}", name="home_construct_charpente_edit")
     * @ParamConverter("charpente", options={"mapping": {"idCharpente": "id"}})
     */
    public function formAction(Request $request, Charpente $charpente=null, GrosOeuvre $grosOeuvre =null)
    {
        $typesCharpente=$this->getDoctrine()->getManager()->getRepository('HomeConstructBuildBundle:TypeCharpente')->findAll();
        if($grosOeuvre){
            $toiture=$grosOeuvre->getToiture();
        }else{
            $toiture=$charpente->getToiture();
        }

        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));
        if(!$charpente){
            $page = array(
                'title' => 'Gros Oeuvre',
                'sub_title' => 'Ajout de la charpente'
            );
            $titreOnglet = "Modif Gros Oeuvre";
            $charpente = new Charpente();
        }else{
            $page = array(
                'title' => 'Gros Oeuvre',
                'sub_title' => 'Modification de la charpente'
            );
            $titreOnglet = "Modif Gros Oeuvre";
            $grosOeuvre=$charpente->getGrosOeuvre();
        }
        // si le gros oeuvre n'a pas de toiture alors on est redirigé vers l'url d'ou l'on vient
        if($grosOeuvre->getToiture()==null){
            $url=$request->headers->get('referer');
            return new RedirectResponse($url);
        }
        $typeFermette = $this->getDoctrine()
            ->getManager()
            ->getRepository('HomeConstructBuildBundle:TypeCharpente')
            ->findOneBy(['nom'=>'Fermette bois']);
        $form = $this->createForm(CharpenteType::class, $charpente,[
            'valueComble'=>$grosOeuvre->getInformationBase()->getComble(),
            'typeFermette'=>$typeFermette,
            'valueMainDoeuvre'=>$charpente->getTarifMainDoeuvre()
        ]);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            //si on est entrain de cree une charpente
            if(!$charpente->getId()){
                $grosOeuvre->setCharpente($charpente);
                $charpente->setGrosOeuvre($grosOeuvre);
                $toiture=$grosOeuvre->getToiture();
                $charpente->setToiture($toiture);
                $toiture->setCharpente($charpente);
                $em->persist($toiture);
                $notif="Informations sur la charpente ajoutées";
            }else{
                $lastPrice=$charpente->getPrix();
                $notif="Informations sur la charpente modifiées";
            }
            $prixAvantCalculGO=$grosOeuvre->getPrix();
            $em->persist($charpente);
            $em->flush();
            $em->persist($grosOeuvre);
            $em->flush();
            $projet=$grosOeuvre->getProjet();
            $em->persist($projet);
            $em->flush();

            $prixApresCalculGO=$grosOeuvre->getPrix();
            $pathHelper->showNotifPriceGrosOeuvre($prixAvantCalculGO,$prixApresCalculGO);
            if(isset($lastPrice)){
                $newPrice=$charpente->getPrix();
                $pathHelper=new PathHelper(null,$request);
                $pathHelper->showNotifPriceChange($charpente,$lastPrice,$newPrice);
            }
            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_charpente_profile',[
                'id'=>$charpente->getId()
            ]);
        };

        return $this->render('@HomeConstructBuild/Charpente/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'grosOeuvre'=>$grosOeuvre,
            'titreOnglet' => $titreOnglet,
            'editMode' => $charpente->getId() !== null,
            'refererIsAForm'=>$refererIsAForm,
            'typesCharpente' =>$typesCharpente,
            'toiture'=>$toiture
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_charpente_delete")
     */
    public function deleteAction(Request $request, Charpente $charpente)
    {
        $grosOeuvre=$charpente->getGrosOeuvre();

        $em = $this->getDoctrine()->getManager();
        $em->remove($charpente);
        $em->flush();
        $request->getSession()->set('entity-just-deleted','charpente');
        $request->getSession()->getFlashBag()->add('notice', 'Informations sur la Charpente supprimées');
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_gros_oeuvre_profile', [
            'id'=>$grosOeuvre->getId()
        ]);
    }

    /**
     * @Route("/profile/view/{id}", name="home_construct_charpente_profile")
     */
    public function profileAction(Request $request,Charpente $charpente)
    {
        $grosOeuvre=$this->getDoctrine()
            ->getManager()
            ->getRepository('HomeConstructBuildBundle:GrosOeuvre')
            ->findOneBy(['charpente'=>$charpente]);

        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        $page = array(
            'title' => 'Gros Oeuvre',
            'sub_title' => 'Infos sur la charpente'
        );
        $titreOnglet = "Charpente";

        $object = 'Charpente';

        return $this->render('@HomeConstructBuild/Charpente/profile.html.twig', array(
            'page' => $page,
            'object' => $object,
            'charpente' => $charpente,
            'titreOnglet' => $titreOnglet,
            'grosOeuvre' => $grosOeuvre,
            'refererIsAForm'=>$refererIsAForm
        ));
    }
}

