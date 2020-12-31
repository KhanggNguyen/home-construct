<?php

namespace HomeConstruct\BuildBundle\Controller\GrosOeuvre;

use HomeConstruct\BuildBundle\Entity\GrosOeuvre;
use HomeConstruct\BuildBundle\Entity\Piece;
use HomeConstruct\BuildBundle\Form\ExcavationType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use HomeConstruct\BuildBundle\Entity\Excavation;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Excavation controller.
 *
 * @Route("/gros-oeuvre/excavation")
 *
 */
class ExcavationController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/{idGrosOeuvre}", name="home_construct_excavation_creation")
     * @ParamConverter("grosOeuvre", options={"mapping": {"idGrosOeuvre": "id"}})
     * @Route("/edit/{idExcavation}", name="home_construct_excavation_edit")
     * @ParamConverter("excavation", options={"mapping": {"idExcavation": "id"}})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function formAction(Request $request,Excavation $excavation=null,GrosOeuvre $grosOeuvre=null)
    {
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));
        if(!$excavation){
            $page = array(
                'title' => 'Gros Oeuvre',
                'sub_title' => 'Ajout des infos sur l\'excavation'
            );
            $titreOnglet = "Modif Gros Oeuvre";
            $excavation = new Excavation();
        }else{
            $page = array(
                'title' => 'Gros Oeuvre',
                'sub_title' => 'Modification des infos sur l\'excavation'
            );
            $titreOnglet = "Modif Gros Oeuvre";
            $grosOeuvre=$excavation->getGrosOeuvre();
        }
        //pour avoir array des type terrassement
        $query = $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeTerrassement')
            ->createQueryBuilder('c')
            ->getQuery();
        $typeTerrassement = $query->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        $form = $this->createForm(ExcavationType::class, $excavation);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $prixAvantCalculGo=$grosOeuvre->getPrix();
            $em = $this->getDoctrine()->getManager();
            if(!$excavation->getId()){
                $grosOeuvre->setExcavation($excavation);
                $excavation->setGrosOeuvre($grosOeuvre);
                $notif='Informations sur l\'excavation ajoutées';
                $creation=true;
                $excavation->setCreateur($this->getUser());
            }else{
                $notif='Informations sur l\'excavation modifiées';
                $lastPrice=$excavation->getPrixTotal();
                $creation=false;
                $excavation->setModifieur($this->getUser());
            }
            $em->persist($excavation);
            $em->flush();
            $em->persist($grosOeuvre);
            $em->flush();
            $projet=$grosOeuvre->getProjet();
            $em->persist($projet);
            $em->flush();
            $prixApresCalculGo=$grosOeuvre->getPrix();
            $pathHelper->showNotifPriceGrosOeuvre($prixAvantCalculGo,$prixApresCalculGo);
            if(isset($lastPrice)){
                $newPrice=$excavation->getPrixTotal();
                $pathHelper->showNotifPriceChange($excavation,$lastPrice,$newPrice);
            }
            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            // fonction qui envoie des mails de notif aux clients du projet
            $pathHelper->sendMailToClientsWhenEdit(
                $this->get('symracine_mail.mailer'),
                $this->getUser(),
                $grosOeuvre,
                $prixAvantCalculGo,
                $prixApresCalculGo,
                $creation,
                'HomeConstructBuildBundle:Mail:excavation_edited'
            );
            return $this->redirectToRoute('home_construct_excavation_profile',[
                'id'=>$excavation->getId()
            ]);
        };
        return $this->render('@HomeConstructBuild/Excavation/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'editMode' => $excavation->getId() !== null,
            'typeTerrassement' => $typeTerrassement,
            'grosOeuvre' => $grosOeuvre,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }


    /**
     * @Route("/suppression/{id}", name="home_construct_excavation_delete")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request, Excavation $excavation)
    {
        $grosOeuvre=$excavation->getGrosOeuvre();
        $em = $this->getDoctrine()->getManager();
        $em->remove($excavation);
        $em->flush();
        //test
        $pathHelper=new PathHelper($em,$request);
        $pathHelper->sendMailToClientsWhenDelete(
            $this->get('symracine_mail.mailer'),
            $this->getUser(),
            $grosOeuvre,
            'HomeConstructBuildBundle:Mail:excavation_deleted'
        );
        $request->getSession()->set('entity-just-deleted','excavation');
        $request->getSession()->getFlashBag()->add('notice', 'Informations sur l\'excavation supprimées');
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_gros_oeuvre_profile', [
            'id'=>$grosOeuvre->getId()
        ]);
    }

    /**
     * @Route("/profile/view/{id}", name="home_construct_excavation_profile")
     */
    public function profileAction(Request $request, Excavation $excavation)
    {
        $grosOeuvre=$excavation->getGrosOeuvre();

        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=$this->container->get('homeconstruct_service.pathhelper');
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        $page = array(
            'title' => 'Gros Oeuvre',
            'sub_title' => 'Infos sur l\'excavation'
        );
        $titreOnglet = "Excavation";

        $object = 'Excavation';

        return $this->render('@HomeConstructBuild/Excavation/profile.html.twig', array(
            'page' => $page,
            'object' => $object,
            'excavation' => $excavation,
            'titreOnglet' => $titreOnglet,
            'grosOeuvre' => $grosOeuvre,
            'refererIsAForm'=>$refererIsAForm
        ));
    }
}

