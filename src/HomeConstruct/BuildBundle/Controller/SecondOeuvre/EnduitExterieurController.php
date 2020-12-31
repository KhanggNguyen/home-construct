<?php

namespace HomeConstruct\BuildBundle\Controller\SecondOeuvre;

use HomeConstruct\BuildBundle\Entity\SecondOeuvre;
use HomeConstruct\BuildBundle\Entity\GrosOeuvre;
use HomeConstruct\BuildBundle\Form\EnduitExterieurType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use HomeConstruct\BuildBundle\Entity\EnduitExterieur;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * EnduitExterieur controller.
 *
 * @Route("/second-oeuvre/enduit-exterieur")
 */
class EnduitExterieurController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/{idSecondOeuvre}", name="home_construct_enduit_exterieur_creation")
     * @ParamConverter("secondOeuvre", options={"mapping": {"idSecondOeuvre": "id"}})
     * @Route("/edit/{idEnduitExterieur}", name="home_construct_enduit_exterieur_edit")
     * @ParamConverter("enduitExterieur", options={"mapping": {"idEnduitExterieur": "id"}})
     */
    public function formAction(Request $request,EnduitExterieur $enduitExterieur=null,SecondOeuvre $secondOeuvre=null)
    {
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));
        if(!$enduitExterieur){
            $page = array(
                'title' => 'Second Oeuvre',
                'sub_title' => 'Ajout des infos sur l\'enduit de façade'
            );
            $titreOnglet = "Modif Second Oeuvre";
            $enduitExterieur = new EnduitExterieur();
        }else{
            $page = array(
                'title' => 'Second Oeuvre',
                'sub_title' => 'Modification des infos sur l\'enduit de façade'
            );
            $titreOnglet = "Modif Second Oeuvre";
            $secondOeuvre=$enduitExterieur->getSecondOeuvre();
        }
        if($secondOeuvre->getProjet()->getGrosOeuvre()){
            if($secondOeuvre->getProjet()->getGrosOeuvre()->getElevation()) {
                $elevation = $secondOeuvre->getProjet()->getGrosOeuvre()->getElevation();
            }else{
                $elevation = null;
            }
        }else{
            $elevation = null;
        }

        $query = $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeEnduitExterieur')
            ->createQueryBuilder('c')
            ->getQuery();
        $typeEnduitExterieur = $query->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        $form = $this->createForm(EnduitExterieurType::class, $enduitExterieur);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $prixAvantCalculSo=$secondOeuvre->getPrix();
            $em = $this->getDoctrine()->getManager();
            if(!$enduitExterieur->getId()){
                $secondOeuvre->setEnduitExterieur($enduitExterieur);
                $enduitExterieur->setSecondOeuvre($secondOeuvre);
                $notif='Informations sur l\'enduit façade ajoutées';
            }else{
                $notif='Informations sur l\'enduit façade modifiées';
                $lastPrice=$enduitExterieur->getPrixTotal();
            }
            $em->persist($enduitExterieur);
            $em->flush();
            $em->persist($secondOeuvre);
            $em->flush();
            $projet=$secondOeuvre->getProjet();
            $em->persist($projet);
            $em->flush();
            if(isset($lastPrice)){
                $newPrice=$enduitExterieur->getPrixTotal();
                $pathHelper->showNotifPriceChange($enduitExterieur,$lastPrice,$newPrice);
            }
            $prixApresCalculSo=$secondOeuvre->getPrix();
            $pathHelper->showNotifPriceSecondOeuvre($prixAvantCalculSo,$prixApresCalculSo);
            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_enduit_exterieur_profile',[
                'id'=>$enduitExterieur->getId()
            ]);
        };

        return $this->render('@HomeConstructBuild/EnduitExterieur/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'editMode' => $enduitExterieur->getId() !== null,
            'secondOeuvre' => $secondOeuvre,
            'elevation' => $elevation,
            'typeEnduitExterieur' => $typeEnduitExterieur,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_enduit_exterieur_delete")
     */
    public function deleteAction(Request $request, EnduitExterieur $enduitExterieur)
    {
        $secondOeuvre=$this->getDoctrine()
            ->getManager()
            ->getRepository('HomeConstructBuildBundle:SecondOeuvre')
            ->findOneBy(['enduitExterieur'=>$enduitExterieur]);
        $em = $this->getDoctrine()->getManager();
        $em->remove($enduitExterieur);
        $em->flush();
        $request->getSession()->set('entity-just-deleted','enduit-exterieur');
        $request->getSession()->getFlashBag()->add('notice', 'Informations sur l\'enduit exterieur supprimées');
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_second_oeuvre_profile', [
            'id'=>$secondOeuvre->getId()
        ]);
    }

    /**
     * @Route("/profile/view/{id}", name="home_construct_enduit_exterieur_profile")
     */
    public function profileAction(Request $request, EnduitExterieur $enduitExterieur)
    {
        $secondOeuvre=$this->getDoctrine()
            ->getManager()
            ->getRepository('HomeConstructBuildBundle:SecondOeuvre')
            ->findOneBy(['enduitExterieur'=>$enduitExterieur]);

        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=$this->container->get('homeconstruct_service.pathhelper');
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        $page = array(
            'title' => 'Second Oeuvre',
            'sub_title' => 'Infos sur l\'enduit de façade'
        );
        $titreOnglet = "Enduit Extérieur";

        $object = 'Enduit Extérieur';

        return $this->render('@HomeConstructBuild/EnduitExterieur/profile.html.twig', array(
            'page' => $page,
            'object' => $object,
            'enduitExterieur' => $enduitExterieur,
            'titreOnglet' => $titreOnglet,
            'secondOeuvre' => $secondOeuvre,
            'refererIsAForm'=>$refererIsAForm
        ));
    }
}

