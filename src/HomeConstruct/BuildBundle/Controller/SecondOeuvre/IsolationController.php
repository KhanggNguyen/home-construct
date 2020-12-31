<?php

namespace HomeConstruct\BuildBundle\Controller\SecondOeuvre;

use HomeConstruct\BuildBundle\Entity\SecondOeuvre;
use HomeConstruct\BuildBundle\Form\IsolationType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use HomeConstruct\BuildBundle\Entity\Isolation;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Isolation controller.
 *
 * @Route("/second-oeuvre/isolation")
 *
 */
class IsolationController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/{idSecondOeuvre}", name="home_construct_isolation_creation")
     * @ParamConverter("secondOeuvre", options={"mapping": {"idSecondOeuvre": "id"}})
     * @Route("/edit/{idIsolation}", name="home_construct_isolation_edit")
     * @ParamConverter("isolation", options={"mapping": {"idIsolation": "id"}})
     */
    public function formAction(Request $request,Isolation $isolation=null,SecondOeuvre $secondOeuvre=null)
    {
        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));
        if(!$isolation){
            $page = array(
                'title' => 'Second Oeuvre',
                'sub_title' => 'Ajout des infos sur l\'isolation'
            );
            $titreOnglet = "Ajout Second Oeuvre";
            $isolation = new Isolation();
        }else{
            $page = array(
                'title' => 'Second Oeuvre',
                'sub_title' => 'Modification des infos sur l\'isolation'
            );
            $titreOnglet = "Modif Second Oeuvre";
            $secondOeuvre=$isolation->getSecondOeuvre();
        }
        $elevation = null;
        $plancher = null;
        $toiture = null;
        $grosOeuvre = null;
        if($secondOeuvre->getProjet()->getGrosOeuvre()){
            if($secondOeuvre->getProjet()->getGrosOeuvre()){
                $grosOeuvre = $secondOeuvre->getProjet()->getGrosOeuvre();
            }
            if($secondOeuvre->getProjet()->getGrosOeuvre()->getElevation()) {
                $elevation = $secondOeuvre->getProjet()->getGrosOeuvre()->getElevation();
            }
            if($secondOeuvre->getProjet()->getGrosOeuvre()->getPlancher()){
                $plancher = $secondOeuvre->getProjet()->getGrosOeuvre()->getPlancher();
            }
            if($secondOeuvre->getProjet()->getGrosOeuvre()->getToiture()){
                $toiture = $secondOeuvre->getProjet()->getGrosOeuvre()->getToiture();
            }
        }

        $typeIsolationMur = $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeIsolationMur')
            ->createQueryBuilder('m')
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        $typeIsolationPlafond = $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeIsolationPlafond')
            ->createQueryBuilder('p')
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        $typeIsolationPlancher = $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeIsolationPlancher')
            ->createQueryBuilder('p')
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        $typeIsolationVitre = $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypeIsolationVitre')
            ->createQueryBuilder('v')
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        $form = $this->createForm(IsolationType::class, $isolation);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $prixAvantCalculSo=$secondOeuvre->getPrix();
            $em = $this->getDoctrine()->getManager();
            if(!$isolation->getId()){
                $secondOeuvre->setIsolation($isolation);
                $isolation->setSecondOeuvre($secondOeuvre);
                $notif='Informations sur l\'isolation ajoutées';
            }else{
                $notif='Informations sur l\'isolation modifiées';
                $lastPrice=$isolation->getPrixTotal();
            }
            $em->persist($isolation);
            $em->flush();
            $em->persist($secondOeuvre);
            $em->flush();
            $projet=$secondOeuvre->getProjet();
            $em->persist($projet);
            $em->flush();
            if(isset($lastPrice)){
                $newPrice=$isolation->getPrixTotal();
                $pathHelper->showNotifPriceChange($isolation,$lastPrice,$newPrice);
            }
            $prixApresCalculSo=$secondOeuvre->getPrix();
            $pathHelper->showNotifPriceSecondOeuvre($prixAvantCalculSo,$prixApresCalculSo);
            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_isolation_profile',[
                'id'=>$isolation->getId()
            ]);
        };

        return $this->render('@HomeConstructBuild/Isolation/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'editMode' => $isolation->getId() !== null,
            'secondOeuvre' => $secondOeuvre,
            'grosOeuvre' => $grosOeuvre,
            'elevation' => $elevation,
            'plancher' => $plancher,
            'toiture' => $toiture,
            'typeIsolationMur' => $typeIsolationMur,
            'typeIsolationPlafond' => $typeIsolationPlafond,
            'typeIsolationPlancher' => $typeIsolationPlancher,
            'typeIsolationVitre' => $typeIsolationVitre,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_isolation_delete")
     */
    public function deleteAction(Request $request, Isolation $isolation)
    {
        $secondOeuvre=$this->getDoctrine()
            ->getManager()
            ->getRepository('HomeConstructBuildBundle:SecondOeuvre')
            ->findOneBy(['isolation'=>$isolation]);
        $em = $this->getDoctrine()->getManager();
        $em->remove($isolation);
        $em->flush();
        $request->getSession()->set('entity-just-deleted','isolation');
        $request->getSession()->getFlashBag()->add('notice', 'Informations sur l\'isolation supprimées');
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_second_oeuvre_profile', [
            'id'=>$secondOeuvre->getId()
        ]);
    }

    /**
     * @Route("/profile/view/{id}", name="home_construct_isolation_profile")
     */
    public function profileAction(Request $request, Isolation $isolation)
    {
        $secondOeuvre=$this->getDoctrine()
            ->getManager()
            ->getRepository('HomeConstructBuildBundle:SecondOeuvre')
            ->findOneBy(['isolation'=>$isolation]);

        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=$this->container->get('homeconstruct_service.pathhelper');
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        $page = array(
            'title' => 'Second Oeuvre',
            'sub_title' => 'Infos sur l\'isolation'
        );
        $titreOnglet = "Isolation";

        $object = 'Isolation';

        return $this->render('@HomeConstructBuild/Isolation/profile.html.twig', array(
            'page' => $page,
            'object' => $object,
            'isolation' => $isolation,
            'titreOnglet' => $titreOnglet,
            'secondOeuvre' => $secondOeuvre,
            'refererIsAForm'=>$refererIsAForm
        ));
    }
}

