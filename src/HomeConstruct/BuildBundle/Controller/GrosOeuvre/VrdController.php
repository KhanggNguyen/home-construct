<?php

namespace HomeConstruct\BuildBundle\Controller\GrosOeuvre;

use HomeConstruct\BuildBundle\Entity\GrosOeuvre;
use HomeConstruct\BuildBundle\Entity\Piece;
use HomeConstruct\BuildBundle\Entity\ReseauEauUsee;
use HomeConstruct\BuildBundle\Form\VrdType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use HomeConstruct\BuildBundle\Entity\Vrd;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Vrd controller.
 *
 * @Route("/gros-oeuvre/vrd")
 */
class VrdController extends Controller
{
    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/{idGrosOeuvre}", name="home_construct_vrd_creation")
     * @ParamConverter("grosOeuvre", options={"mapping": {"idGrosOeuvre": "id"}})
     * @Route("/edit/{idVrd}", name="home_construct_vrd_edit")
     * @ParamConverter("vrd", options={"mapping": {"idVrd": "id"}})
     */
    public function formAction(Request $request,Vrd $vrd=null,GrosOeuvre $grosOeuvre=null)
    {
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));
        $fournisseursGazNaturel=$this->getDoctrine()->getManager()->getRepository('HomeConstructBuildBundle:ReseauGazNaturel')->findAll();

        if(!$vrd){
            $page = array(
                'title' => 'Gros Oeuvre',
                'sub_title' => 'Ajout des infos sur la voirie et réseaux divers'
            );
            $titreOnglet = "Modif Gros Oeuvre";
            $vrd = new Vrd($this->getDoctrine()->getManager());
        }else{
            $page = array(
                'title' => 'Gros Oeuvre',
                'sub_title' => 'Modification des infos sur la voirie et réseaux divers'
            );
            $titreOnglet = "Modif Gros Oeuvre";
            $grosOeuvre = $vrd->getGrosOeuvre();
            $vrd->setEm($this->getDoctrine()->getManager());
        }
        /*if($vrd->getReseauEauUsee()){
            $form = $this->createForm(VrdType::class, $vrd,[
                'valuePompe'=>$vrd->getReseauEauUsee()->getPompeRelevage(),
                'valueStation'=>$vrd->getReseauEauUsee()->getMicroStation(),
                'valueEtude'=>$vrd->getReseauEauUsee()->getEtudeHydro(),
                'valueFosse'=>$vrd->getReseauEauUsee()->getFosseSeptique()
        ]);
        }else{
            $form = $this->createForm(VrdType::class, $vrd,[
                'valuePompe'=>null,'valueStation'=>null,'valueEtude'=>null,'valueFosse'=>null
            ]);
        }*/
        $form = $this->createForm(VrdType::class, $vrd,[
            'valueGazNaturel'=>$vrd->getReseauGazNaturel()
        ]);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $prixAvantCalculGo=$grosOeuvre->getPrix();
            $em = $this->getDoctrine()->getManager();
            if(!$vrd->getId()){
                $grosOeuvre->setVrd($vrd);
                $vrd->setGrosOeuvre($grosOeuvre);
                    $notif = "Informations sur la VRD ajoutées";
            }else{
                $notif = "Informations sur la VRD modifiées";
                $lastPrice=$vrd->getPrixTotal();
            }
            $em->persist($vrd);
            $em->flush();
            $vrd->calculPrix();
            $em->persist($vrd);
            $em->flush();
            $em->persist($grosOeuvre);
            $em->flush();
            $prixApresCalculGo=$grosOeuvre->getPrix();
            $pathHelper->showNotifPriceGrosOeuvre($prixAvantCalculGo,$prixApresCalculGo);
            if(isset($lastPrice)){
                $newPrice=$vrd->getPrixTotal();
                $pathHelper->showNotifPriceChange($vrd,$lastPrice,$newPrice);
            }
            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_vrd_profile',[
                'id'=>$vrd->getId()
            ]);
        };

        return $this->render('@HomeConstructBuild/Vrd/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'editMode' => $vrd->getId() !== null,
            'grosOeuvre' => $grosOeuvre,
            'refererIsAForm'=>$refererIsAForm,
            'fournisseursGazNaturel'=>$fournisseursGazNaturel
        ]);
    }


    /**
     * @Route("/suppression/{id}", name="home_construct_vrd_delete")
     */
    public function deleteAction(Request $request, Vrd $vrd)
    {
        $grosOeuvre=$this->getDoctrine()
            ->getManager()
            ->getRepository('HomeConstructBuildBundle:GrosOeuvre')
            ->findOneBy(['vrd'=>$vrd]);
        $notif="Informations sur la VRD supprimées";
        $em = $this->getDoctrine()->getManager();
        $em->remove($vrd);
        $em->flush();
        $request->getSession()->set('entity-just-deleted','vrd');
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_gros_oeuvre_profile', [
            'id'=>$grosOeuvre->getId()
        ]);
    }

    /**
     * @Route("/profile/view/{id}", name="home_construct_vrd_profile")
     */
    public function profileAction(Request $request, Vrd $vrd)
    {
        $grosOeuvre=$this->getDoctrine()
            ->getManager()
            ->getRepository('HomeConstructBuildBundle:GrosOeuvre')
            ->findOneBy(['vrd'=>$vrd]);

        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=$this->container->get('homeconstruct_service.pathhelper');
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        $page = array(
            'title' => 'Gros Oeuvre',
            'sub_title' => 'Infos sur la voirie et les réseaux divers'
        );
        $titreOnglet = "Vrd";

        $object = 'Vrd';

        return $this->render('@HomeConstructBuild/Vrd/profile.html.twig', array(
            'page' => $page,
            'object' => $object,
            'vrd' => $vrd,
            'titreOnglet' => $titreOnglet,
            'grosOeuvre' => $grosOeuvre,
            'refererIsAForm' => $refererIsAForm
        ));
    }
}

