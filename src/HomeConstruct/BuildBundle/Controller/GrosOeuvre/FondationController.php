<?php

namespace HomeConstruct\BuildBundle\Controller\GrosOeuvre;

use HomeConstruct\BuildBundle\Entity\Ferraillage;
use HomeConstruct\BuildBundle\Entity\GrosOeuvre;
use HomeConstruct\BuildBundle\Entity\Piece;
use HomeConstruct\BuildBundle\Form\FondationType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use HomeConstruct\BuildBundle\Entity\Fondation;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Fondation controller.
 *
 * @Route("/gros-oeuvre/fondations")
 */
class FondationController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/{idGrosOeuvre}", name="home_construct_fondation_creation")
     * @ParamConverter("grosOeuvre", options={"mapping": {"idGrosOeuvre": "id"}})
     * @Route("/edit/{idFondation}", name="home_construct_fondation_edit")
     * @ParamConverter("fondation", options={"mapping": {"idFondation": "id"}})
     */
    public function formAction(Request $request,Fondation $fondation=null,GrosOeuvre $grosOeuvre=null)
    {
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));
        if(!$fondation){
            $page = array(
                'title' => 'Gros Oeuvre',
                'sub_title' => 'Ajout des infos sur les fondations'
            );
            $titreOnglet = "Modif Gros Oeuvre";
            $fondation = new Fondation();
        }else{
            $page = array(
                'title' => 'Gros Oeuvre',
                'sub_title' => 'Modification des infos sur les fondations'
            );
            $titreOnglet = "Modif Gros Oeuvre";
            $grosOeuvre=$fondation->getGrosOeuvre();
        }
        $form = $this->createForm(FondationType::class, $fondation,[
            'valueMainDoeuvre'=>$fondation->getPrixMainDoeuvre(),
            'user'=>$this->getUser()
        ]);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!$fondation->getId()){
                $grosOeuvre->setFondation($fondation);
                $fondation->setGrosOeuvre($grosOeuvre);
                if(!($this->getUser()->hasGroup('SUPER ADMIN')) and !($this->getUser()->hasGroup('PROFESSIONNEL'))) {
                    $ferraillage = new Ferraillage();
                    $ferraillage->setQuantite($grosOeuvre->getInformationBase()->getSurfaceTotale());
                    $typeFerraillage = $em->getRepository("HomeConstructBuildBundle:TypeFerraillage")
                        ->findOneBy(["nom" => '15/35']);
                    $ferraillage->setType($typeFerraillage);
                    $fondation->setFerraillage($ferraillage);
                    $em->persist($ferraillage);
                }
                $notif='Informations sur les fondations ajoutées';
            }else{
                $notif='Informations sur les fondations modifiées';
                $lastPrice=$fondation->getPrix();
            }
            $em->persist($fondation);
            $lastPriceGO=$grosOeuvre->getPrix();
            $em->persist($grosOeuvre);
            $em->flush();
            $projet=$grosOeuvre->getProjet();
            $em->persist($projet);
            $em->flush();
            $newPriceGO=$grosOeuvre->getPrix();
            if(isset($lastPrice)){
                $newPrice=$fondation->getPrix();
                $pathHelper->showNotifPriceChange($fondation,$lastPrice,$newPrice);
            }
            $pathHelper->showNotifPriceGrosOeuvre($lastPriceGO,$newPriceGO);
            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_fondation_profile',[
                'id'=>$fondation->getId()
            ]);
        };

        return $this->render('@HomeConstructBuild/Fondation/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'editMode' => $fondation->getId() !== null,
            'grosOeuvre' => $grosOeuvre,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }


    /**
     * @Route("/suppression/{id}", name="home_construct_fondation_delete")
     */
    public function deleteAction(Request $request, Fondation $fondation)
    {
        $grosOeuvre=$this->getDoctrine()
            ->getManager()
            ->getRepository('HomeConstructBuildBundle:GrosOeuvre')
            ->findOneBy(['fondation'=>$fondation]);
        $em = $this->getDoctrine()->getManager();
        $em->remove($fondation);
        $em->flush();
        $request->getSession()->set('entity-just-deleted','fondation');
        $request->getSession()->getFlashBag()->add('notice', 'Informations sur les fondations supprimées');
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_gros_oeuvre_profile', [
            'id'=>$grosOeuvre->getId()
        ]);
    }

    /**
     * @Route("/profile/view/{id}", name="home_construct_fondation_profile")
     */
    public function profileAction(Request $request, Fondation $fondation)
    {
        $grosOeuvre=$this->getDoctrine()
            ->getManager()
            ->getRepository('HomeConstructBuildBundle:GrosOeuvre')
            ->findOneBy(['fondation'=>$fondation]);

        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=$this->container->get('homeconstruct_service.pathhelper');
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));
        $page = array(
            'title' => 'Gros Oeuvre',
            'sub_title' => 'Infos sur les fondations'
        );

        $titreOnglet = "Fondation";

        $object = 'Fondation';

        return $this->render('@HomeConstructBuild/Fondation/profile.html.twig', array(
            'page' => $page,
            'object' => $object,
            'fondation' => $fondation,
            'titreOnglet' => $titreOnglet,
            'grosOeuvre' => $grosOeuvre,
            'refererIsAForm'=>$refererIsAForm
        ));
    }
}

