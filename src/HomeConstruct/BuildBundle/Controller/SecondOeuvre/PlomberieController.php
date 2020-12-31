<?php

namespace HomeConstruct\BuildBundle\Controller\SecondOeuvre;

use HomeConstruct\BuildBundle\Entity\SecondOeuvre;
use HomeConstruct\BuildBundle\Entity\Plomberie;
use HomeConstruct\BuildBundle\Form\PlomberieType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use HomeConstruct\BuildBundle\Entity\Projet;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Plomberie controller.
 *
 * @Route("/second-oeuvre/plomberie")
 */
class PlomberieController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/edit/{idPlomberie}", name="home_construct_plomberie_edit")
     * @ParamConverter("plomberie", options={"mapping": {"idPlomberie": "id"}})
     * @Route("/creation/{idSecondOeuvre}", name="home_construct_plomberie_creation")
     * @ParamConverter("secondOeuvre", options={"mapping": {"idSecondOeuvre": "id"}})
     */
    public function formAction(Request $request, SecondOeuvre $secondOeuvre=null, Plomberie $plomberie=null)
    {
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        if(!$plomberie){
            $plomberie = new Plomberie($this->getDoctrine()->getManager());
            $page = array(
                'title' => 'Second Oeuvre',
                'sub_title' => 'Ajout de plomberie(s)'
            );
            $titreOnglet = "Ajout Plomberie";
        }else{
            $secondOeuvre = $plomberie->getSecondOeuvre();
            $page = array(
                'title' => 'Second Oeuvre',
                'sub_title' => 'Modification de plomberie(s)'
            );
            $plomberie->setEm($this->getDoctrine()->getManager());
            $titreOnglet = "Modification plomberie";
        }
        $entities= $secondOeuvre->getPlomberies();
        $typeSdB = $this->getDoctrine()
            ->getRepository('HomeConstructBuildBundle:TypePlomberie')
            ->createQueryBuilder('sdb')
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        $form = $this->createForm(PlomberieType::class, $plomberie);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid() && $form->isSubmitted()) {
            $prixAvantCalculSO=$secondOeuvre->getPrix();
            $em = $this->getDoctrine()->getManager();
            $plomberieExiste = $em->getRepository('HomeConstructBuildBundle:Plomberie')
                    ->findOneBy(['typePlomberie' => $plomberie->getTypePlomberie(),
                        'secondOeuvre' => $secondOeuvre]);
            if(!$plomberie->getId()){
                if($plomberieExiste){
                    $plomberie->setSecondOeuvre($secondOeuvre);
                    $secondOeuvre->addPlomberie($plomberie);
                    $plomberie->setM2(($plomberieExiste->getM2() + $plomberie->getM2()));
                    $plomberie->setQuantite(($plomberieExiste->getQuantite() + $plomberie->getQuantite()));
                    $em->remove($plomberieExiste);
                    $em->persist($plomberie);
                    $em->flush();
                }else{
                    $plomberie->setSecondOeuvre($secondOeuvre);
                    $secondOeuvre->addPlomberie($plomberie);
                    $em->persist($plomberie);
                    $em->flush();
                }
                $notif = $plomberie->getQuantite()." ".$plomberie->getTypePlomberie()->getNom()." ajouté(e)(s)";
            }else{
                $em->persist($plomberie);
                $em->flush();
                $notif = "Plomberie modifiée";
            }
            $prixApresCalculSO=$secondOeuvre->getPrix();
            $pathHelper->showNotifPriceSecondOeuvre($prixAvantCalculSO,$prixApresCalculSO);
            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            return $this->redirectToRoute('home_construct_plomberie_creation',[
                'idSecondOeuvre'=>$secondOeuvre->getId()
            ]);
        }
        return $this->render('@HomeConstructBuild/Plomberie/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'entities' => $entities,
            'typeSdB' => $typeSdB,
            'secondOeuvre' => $secondOeuvre,
            'refererIsAForm'=>$refererIsAForm
        ]);
    }


    /**
     * @Route("/suppression/{id}", name="home_construct_plomberie_deleteone")
     */
    public function deleteOneAction(Request $request, Plomberie $plomberie)
    {
        $notif = $plomberie->getQuantite()." ".$plomberie->getType()->getNom()." supprimé(e)(s)";
        $secondOeuvre=$plomberie->getSecondOeuvre();
        $prixAvantCalculSo=$secondOeuvre->getPrix();
        $em = $this->getDoctrine()->getManager();
        $em->remove($plomberie);
        $em->flush();
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $pathHelper->calculPrixSoAndProjet($secondOeuvre);
        $prixApresCalculSo=$secondOeuvre->getPrix();
        $pathHelper->showNotifPriceSecondOeuvre($prixAvantCalculSo,$prixApresCalculSo);
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_plomberie_list', [
            'idSecondOeuvre'=>$secondOeuvre->getId()
        ]);
    }

    /**
     * @Route("/suppression-plomberies/{idSecondOeuvre}", name="home_construct_plomberie_deleteall")
     * @ParamConverter("secondOeuvre", options={"mapping": {"idSecondOeuvre": "id"}})
     */
    public function deleteAllAction(Request $request, SecondOeuvre $secondOeuvre)
    {
        $prixAvantCalculSo=$secondOeuvre->getPrix();
        $em = $this->getDoctrine()->getManager();
        $notif = "Informations sur la plomberie supprimées";
        $plomberies=$secondOeuvre->getPlomberies();
        foreach ($plomberies as $plomberie){
            $em->remove($plomberie);
        }
        $em->flush();
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $pathHelper->calculPrixSoAndProjet($secondOeuvre);
        $prixApresCalculSo=$secondOeuvre->getPrix();
        $pathHelper->showNotifPriceSecondOeuvre($prixAvantCalculSo,$prixApresCalculSo);
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_second_oeuvre_profile', [
            'id'=>$secondOeuvre->getId()
        ]);
    }

    /**
     * @Route("/liste/{idSecondOeuvre}", name="home_construct_plomberie_list")
     * @ParamConverter("secondOeuvre", options={"mapping": {"idSecondOeuvre": "id"}})
     */
    public function listAction(Request $request, SecondOeuvre $secondOeuvre)
    {
        $page = array(
            'title' => 'Second Oeuvre',
            'sub_title' => 'Vos plomberies'
        );

        $titreOnglet = "Plomberies";

        $object = 'Plomberies';

        $entities = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('HomeConstructBuildBundle:Plomberie')
            ->findBy(['secondOeuvre'=>$secondOeuvre]);

        // l'utilisateur est redirigé vers la vue d'affichage des listes
        return $this->render('@HomeConstructBuild/Plomberie/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'secondOeuvre' => $secondOeuvre,
            'object' => $object,
            'titreOnglet' => $titreOnglet
        ));
    }
}
