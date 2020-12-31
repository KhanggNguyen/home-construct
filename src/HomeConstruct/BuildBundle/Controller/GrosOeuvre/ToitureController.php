<?php

namespace HomeConstruct\BuildBundle\Controller\GrosOeuvre;

use HomeConstruct\BuildBundle\Entity\GrosOeuvre;
use HomeConstruct\BuildBundle\Entity\Piece;
use HomeConstruct\BuildBundle\Form\ToitureType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use HomeConstruct\BuildBundle\Entity\Toiture;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Knp\Bundle\SnappyBundle\Snappy\Response;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;

/**
 * Toiture controller.
 *
 * @Route("/gros-oeuvre/toiture")
 */
class ToitureController extends Controller
{

    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation/{idGrosOeuvre}", name="home_construct_toiture_creation")
     * @ParamConverter("grosOeuvre", options={"mapping": {"idGrosOeuvre": "id"}})
     * @Route("/edit/{idToiture}", name="home_construct_toiture_edit")
     * @ParamConverter("toiture", options={"mapping": {"idToiture": "id"}})
     */
    public function formAction(Request $request,Toiture $toiture=null,GrosOeuvre $grosOeuvre=null)
    {
        // pour le calcul des prix en JS dans le formulaire
        $typesCouverture=$this->getDoctrine()->getManager()->getRepository('HomeConstructBuildBundle:TypeCouverture')->findAll();

        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour)
        $pathHelper=new PathHelper($this->getDoctrine()->getManager(),$request);
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));
        // si on effectue une creation
        if(!$toiture){
            $page = array(
                'title' => 'Gros Oeuvre',
                'sub_title' => 'Ajout des infos sur la toiture'
            );
            $titreOnglet = "Ajout Gros Oeuvre";
            $toiture = new Toiture();
        }else{ // si on effectue une modification
            $page = array(
                'title' => 'Gros Oeuvre',
                'sub_title' => 'Modification des infos sur la toiture'
            );
            $titreOnglet = "Modif Gros Oeuvre";
            $grosOeuvre = $toiture->getGrosOeuvre();
        }

        $valueExpoVent=$toiture->getExpoVent();
        // on cree le formulaire correspondant, les parametres valueXXX sont utiles en cas
        // de modification
        $form = $this->createForm(ToitureType::class, $toiture,[
            'valueExpoVent'=>$valueExpoVent,
            'valueMainDoeuvre'=>$toiture->getTarifMainDoeuvre()
        ]);
        // si la methode du formulaire est POST et qu'il est valide
        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            // si on effectue une creation
            if(!$toiture->getId()){
                $grosOeuvre->setToiture($toiture);
                $toiture->setGrosOeuvre($grosOeuvre);
                $notif="Informations sur la toiture ajoutées";
            }else{ // si on effectue une modification
                $lastPrice=$toiture->getPrix();
                $notif="Informations sur la toiture modifiées";
            }
            $prixAvantCalculGO=$grosOeuvre->getPrix();
            // l'entite toiture est sauvegarde en memoire
            $em->persist($toiture);
            // toutes les entites qui ont sauvegardes en memoire
            // sont enregistrées définitivement en base de données
            $em->flush();

            $tab=array(
                $grosOeuvre->getCharpente()
            );
            // cette fonction permet de faire les appels nécessaires pour les calculs de prix
            // l'affichage des notifications est aussi géré
            $pathHelper->calculPrixAfterEditGo($tab,$grosOeuvre);
            $em->flush();
            $prixApresCalculGO=$grosOeuvre->getPrix();
            $pathHelper->showNotifPriceGrosOeuvre($prixAvantCalculGO,$prixApresCalculGO);

            if(isset($lastPrice)){
                $newPrice=$toiture->getPrix();
                $pathHelper->showNotifPriceChange($toiture,$lastPrice,$newPrice);
            }
            // on se sert ici des variables de session pour générer une notification
            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            // on est redirigé vers le profil de la toiture
            return $this->redirectToRoute('home_construct_toiture_profile',[
                'id'=>$toiture->getId()
            ]);
        };
        // si le formulaire n'est pas valide on est redirigé vers sa vue
        return $this->render('@HomeConstructBuild/Toiture/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'editMode' => $toiture->getId() !== null,
            'grosOeuvre' => $grosOeuvre,
            'refererIsAForm'=>$refererIsAForm,
            'typesCouverture'=>$typesCouverture
        ]);
    }

    /**
     * @Route("/suppression/{id}", name="home_construct_toiture_delete")
     */
    public function deleteAction(Request $request, Toiture $toiture)
    {
        $grosOeuvre=$this->getDoctrine()
            ->getManager()
            ->getRepository('HomeConstructBuildBundle:GrosOeuvre')
            ->findOneBy(['toiture'=>$toiture]);
        $em = $this->getDoctrine()->getManager();
        $em->remove($toiture);
        $em->flush();
        $request->getSession()->set('entity-just-deleted','toiture');
        $request->getSession()->getFlashBag()->add('notice', "Informations sur la toiture et la charpente supprimées");
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirectToRoute('home_construct_gros_oeuvre_profile', [
            'id'=>$grosOeuvre->getId()
        ]);
    }

    /**
     * @Route("/profile/view/{id}", name="home_construct_toiture_profile")
     */
    public function profileAction(Request $request, Toiture $toiture)
    {
        $grosOeuvre=$this->getDoctrine()
            ->getManager()
            ->getRepository('HomeConstructBuildBundle:GrosOeuvre')
            ->findOneBy(['toiture'=>$toiture]);

        // pour tester si on vient d'un formulaire (afin de gerer le lien pour le bouton retour de la page profile)
        $pathHelper=$this->container->get('homeconstruct_service.pathhelper');
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        $page = array(
            'title' => 'Gros Oeuvre',
            'sub_title' => 'Infos sur la toiture'
        );
        $titreOnglet = "Toiture";

        $object = 'Toiture';

        return $this->render('@HomeConstructBuild/Toiture/profile.html.twig', array(
            'page' => $page,
            'object' => $object,
            'toiture' => $toiture,
            'titreOnglet' => $titreOnglet,
            'grosOeuvre' => $grosOeuvre,
            'refererIsAForm' => $refererIsAForm
        ));
    }

    /**
     * Export to PDF
     *
     * @Route("/pdf/{id}", name="home_construct_toiture_pdf")
     */
    public function pdfAction(Request $request,Toiture $toiture)
    {
        $grosOeuvre=$toiture->getGrosOeuvre();
        $pathHelper=$this->container->get('homeconstruct_service.pathhelper');
        $refererIsAForm=$pathHelper->pathIsAForm($request->headers->get('referer'));

        $page = array(
            'title' => 'Gros Oeuvre',
            'sub_title' => 'Infos sur la toiture'
        );
        $titreOnglet = "Toiture";

        $object = 'Toiture';

        $html = $this->renderView('@HomeConstructBuild/Toiture/profile.html.twig', array(
            'page' => $page,
            'object' => $object,
            'toiture' => $toiture,
            'titreOnglet' => $titreOnglet,
            'grosOeuvre' => $grosOeuvre,
            'refererIsAForm' => $refererIsAForm
        ));


        $filename = sprintf('test-%s.pdf', date('Y-m-d'));

        /*$this->get('knp_snappy.pdf')->generateFromHtml(
            $this->renderView(
                '@HomeConstructBuild/Toiture/layout-pdf.html.twig',[
                    'toiture' => $toiture,
                    'grosOeuvre'=>$grosOeuvre
                ]
            ),
            $filename
        );*/

        $html = $this->renderView(
            '@HomeConstructBuild/PDF/layout-pdf.html.twig',[
                'toiture' => $toiture,
                'grosOeuvre'=>$grosOeuvre,
                'page' => $page,
                'object' => $object,
            ]
        );

        return new PdfResponse(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            'file.pdf'
        );


        /*return new Response\PdfResponse(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
            ]
        );*/
    }

}

