<?php

namespace HomeConstruct\BuildBundle\Controller;

use HomeConstruct\BuildBundle\Form\ProjetType;
use HomeConstruct\BuildBundle\Service\PathHelper;
use HomeConstruct\UserBundle\Entity\Users;
use HomeConstruct\UserBundle\Form\EmailForLinkToProjectType;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use HomeConstruct\BuildBundle\Entity\Projet;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Projet controller.
 *
 * @Route("/projet")
 * @Security("has_role('ROLE_USER')")
 */
class ProjetController extends Controller
{
    /**
     * @Route("/accueil")
     */
    public function indexAction()
    {
        return $this->render('@HomeConstructBuild/Default/index.html.twig');
    }

    /**
     * @Route("/creation", name="home_construct_projet_creation")
     * @Route("/edit/{id}", name="home_construct_projet_edit")
     */
    public function formAction(Request $request,Projet $projet=null)
    {
        $user = $this->getUser();
        if(!$projet){
            $page = array(
                'title' => 'Projet',
                'sub_title' => 'Création d\'un nouveau projet'
            );
            $titreOnglet = "Création Projet";
            $projet = new Projet();
        }else{
            if(!$this->getUser()->hasGroup('SUPER ADMIN')) {
                // si le projet n'appartient à l'user courant alors il a pas le droit de le voir
                // on le redirige vers une page d'erreur
                if (!in_array($this->getUser(), $projet->getUsers()->toArray())) {
                    $page = array(
                        'title' => 'Modification projet',
                        'sub_title' => 'Erreur d\'accès'
                    );
                    return $this->render('@HomeConstructBuild/Error/error_access.html.twig', [
                        'page' => $page
                    ]);
                }
            }

            $page = array(
                'title' => 'Projet',
                'sub_title' => 'Modification du projet'
            );
            $titreOnglet = "Modification Projet";
        }
        $form = $this->createForm(ProjetType::class, $projet);

        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!$projet->getId()){
                $projet->setCreateur($user);
                $user->addProjet($projet);
                $etat = $em
                    ->getRepository('HomeConstructBuildBundle:EtatProjet')
                    ->findOneBy(['nom'=>'En attente']);
                $projet->setEtat($etat);
                $date = date('d/m/Y H:i');
                $date = date_create_from_format('d/m/Y H:i', $date);
                $projet->setDateCreation($date);
                $notif='Projet "'.$projet->getNom(). '" crée';
                $em->persist($projet);
                $em->persist($user);
                $em->flush();
                $request->getSession()->getFlashBag()->add('notice', $notif);
                $request->getSession()->getFlashBag()->add('notif', 'True');
                return $this->redirectToRoute('home_construct_projet_profile',[
                    'id'=>$projet->getId()
                ]);
            }else{
                $notif='Projet "'.$projet->getNom(). '" modifiée';
                $em->persist($projet);
                $em->flush();
                $request->getSession()->getFlashBag()->add('notice', $notif);
                $request->getSession()->getFlashBag()->add('notif', 'True');
                return $this->redirectToRoute('home_construct_projet_list');
            }


        };

        return $this->render('@HomeConstructBuild/Projet/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet' => $titreOnglet,
            'editMode' => $projet->getId() !== null
        ]);
    }

    /**
     * @Route("/suppression/{idProjet}", name="home_construct_projet_delete")
     * @ParamConverter("projet", options={"mapping": {"idProjet": "id"}})
     */
    public function deleteAction(Request $request,Projet $projet)
    {
        if(!$this->getUser()->hasGroup('SUPER ADMIN')){
            // si le projet n'appartient à l'user courant alors il a pas le droit de le voir
            // on le redirige vers une page d'erreur
            if(!in_array($this->getUser(),$projet->getUsers()->toArray())){
                $page = array(
                    'title' => 'Suppression d\'un projet',
                    'sub_title'=>'Erreur d\'accès'
                );
                return $this->render('@HomeConstructBuild/Error/error_access.html.twig',[
                    'page'=>$page
                ]);
            }
        }

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $request->getSession()->getFlashBag()->add('notice', 'Projet "'.$projet->getNom(). '" supprimé');
        $request->getSession()->getFlashBag()->add('notif', 'True');
        $em = $this->getDoctrine()->getManager();
        $em->remove($projet);
        $em->flush();
        return $this->redirectToRoute('home_construct_projet_list');
    }

    /**
     * @Route("/liste/{etat}", name="home_construct_projet_list")
     */
    public function listAction(Request $request,$etat=null)
    {
        if($etat){
            switch($etat){
                case 'Tout':
                    $subTitle='Tous vos projets';
                    break;
                case 'En cours':
                    $subTitle='Vos projets en cours';
                    break;
                case 'En attente':
                    $subTitle='Vos projets en attente';
                    break;
                case 'Terminé':
                    $subTitle='Vos projets terminés';
                    break;
                case 'Archivé':
                    $subTitle='Vos projets archivés';
                    break;
            }
        }else{
            $subTitle='Tous vos projets';
        }
        $page = array(
            'title' => 'Projets',
            'sub_title' => $subTitle
        );

        $titreOnglet = "Projets";
        $object = 'Projet';
        if(!$etat){
            if($this->getUser()->hasGroup('SUPER ADMIN')){
                $projets=$this
                    ->getDoctrine()
                    ->getManager()
                    ->getRepository('HomeConstructBuildBundle:Projet')
                    ->findAll();
            }else{
                $projets=$this
                    ->getDoctrine()
                    ->getManager()
                    ->getRepository('HomeConstructBuildBundle:Projet')
                    ->getProjectsByUser($this->getUser());
            }
            $entities=$this
                ->getDoctrine()
                ->getManager()
                ->getRepository('HomeConstructBuildBundle:Projet')
                ->getProjectsWithoutArchive($projets);
        }else{
            if($etat!='Tout'){
                $etatBd=$this
                    ->getDoctrine()
                    ->getManager()
                    ->getRepository('HomeConstructBuildBundle:EtatProjet')
                    ->findOneBy(['nom'=>$etat]);
                $entities = $this
                    ->getDoctrine()
                    ->getManager()
                    ->getRepository('HomeConstructBuildBundle:Projet')
                    ->getProjectsByEtatAndUser($this->getUser(),$etatBd);
            }else{
                if($this->getUser()->hasGroup('SUPER ADMIN')){
                    $projets=$this
                        ->getDoctrine()
                        ->getManager()
                        ->getRepository('HomeConstructBuildBundle:Projet')
                        ->findAll();
                }else{
                    $projets=$this
                        ->getDoctrine()
                        ->getManager()
                        ->getRepository('HomeConstructBuildBundle:Projet')
                        ->getProjectsByUser($this->getUser());
                }
                $entities=$this
                    ->getDoctrine()
                    ->getManager()
                    ->getRepository('HomeConstructBuildBundle:Projet')
                    ->getProjectsWithoutArchive($projets);
            }
        }
        // l'utilisateur est redirigé vers la vue d'affichage des listes
        return $this->render('@HomeConstructBuild/Projet/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'object' => $object,
            'titreOnglet' => $titreOnglet
        ));
    }
    /**
     * @Route("/profile/view/{id}", name="home_construct_projet_profile")
     */
    public function profileAction(Request $request, Projet $projet)
    {
        if(!$this->getUser()->hasGroup('SUPER ADMIN')) {
            // si le projet n'appartient à l'user courant alors il a pas le droit de le voir
            // on le redirige vers une page d'erreur
            if (!in_array($this->getUser(), $projet->getUsers()->toArray())) {
                $page = array(
                    'title' => 'Infos projet',
                    'sub_title' => 'Erreur d\'accès'
                );
                return $this->render('@HomeConstructBuild/Error/error_access.html.twig', [
                    'page' => $page
                ]);
            }
        }

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $grosOeuvre=$projet->getGrosOeuvre();
        $secondOeuvre=$projet->getSecondOeuvre();
        //si le projet a un gros oeuvre (le idGrosOeuvre est requis pour la view pour la definition des redirections)
        if($grosOeuvre){
            $idGrosOeuvre=$grosOeuvre->getId();
        }else{
            $idGrosOeuvre=null;
        }
        //si le projet a un second oeuvre (le idSecondOeuvre est requis pour la view pour la definition des redirections)
        if($secondOeuvre){
            $idSecondOeuvre=$secondOeuvre->getId();
        }else{
            $idSecondOeuvre=null;
        }

        $titleProfileProjet="Projet : \"".$projet->getNom()."\"";

        $titreOnglet = "Projets";


        $object = 'Projet profile';
        return $this->render('@HomeConstructBuild/Projet/profile.html.twig', array(
            'idProjet' => $projet->getId(),
            'titreOnglet' => $titreOnglet,
            'projet'=>$projet,
            'idGrosOeuvre' => $idGrosOeuvre,
            'idSecondOeuvre' => $idSecondOeuvre,
            'titleProfileProjet'=>$titleProfileProjet
        ));
    }

    /**
     * @Route("/changement-etat/{idProjet}/{etat}", name="home_construct_projet_etat_change")
     * @ParamConverter("projet", options={"mapping": {"idProjet": "id"}})
     */
    public function changeEtatAction(Request $request,Projet $projet,$etat)
    {
        if(!$this->getUser()->hasGroup('SUPER ADMIN')) {
            // si le projet n'appartient à l'user courant alors il a pas le droit de le voir
            // on le redirige vers une page d'erreur
            if (!in_array($this->getUser(), $projet->getUsers()->toArray())) {
                $page = array(
                    'title' => 'Changement état projet',
                    'sub_title' => 'Erreur d\'accès'
                );
                return $this->render('@HomeConstructBuild/Error/error_access.html.twig', [
                    'page' => $page
                ]);
            }
        }

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if($etat!="En cours" and $etat!="En attente" and $etat!="Terminé" and $etat!="Archivé"){
            return $this->redirect($request->headers->get('referer'));
        }else{
            $etatPrecedent=$projet->getEtat();
            $etatNouveau = $this->getDoctrine()->getManager()
                ->getRepository('HomeConstructBuildBundle:EtatProjet')
                ->findOneBy(['nom'=>$etat]);
            $projet->setEtat($etatNouveau);
            $request->getSession()->getFlashBag()->add('notice', 'Projet "'.$projet->getNom(). '" est passé de l\'état "'.$etatPrecedent->getNom().'" à l\'état "'.$etatNouveau->getNom().'"');
            $request->getSession()->getFlashBag()->add('notif', 'True');
            $em = $this->getDoctrine()->getManager();
            $em->persist($projet);
            $em->flush();
            return $this->redirect($request->headers->get('referer'));
        }
    }

    /**
     * Export to PDF
     *
     * @Route("/pdf/{id}", name="home_construct_projet_pdf")
     */
    public function pdfAction(Request $request,Projet $projet)
    {
        if(!$this->getUser()->hasGroup('SUPER ADMIN')) {
            // si le projet n'appartient à l'user courant alors il a pas le droit de le voir
            // on le redirige vers une page d'erreur
            if (!in_array($this->getUser(), $projet->getUsers()->toArray())) {
                $page = array(
                    'title' => 'Conversion du projet en PDF',
                    'sub_title' => 'Erreur d\'accès'
                );
                return $this->render('@HomeConstructBuild/Error/error_access.html.twig', [
                    'page' => $page
                ]);
            }
        }

        $page = array(
            'title' => 'Projet',
            'sub_title' => 'Infos sur le projet'
        );
        $titreOnglet = "Projet";

        $object = 'Projet';

        $filename = sprintf('projet-devis-%s.pdf', date('Y-m-d'));

        $html = $this->renderView(
            '@HomeConstructBuild/PDF/layout-pdf.html.twig',[
                'projet' => $projet,
                'page' => $page,
                'object' => $object,
            ]
        );
        return new PdfResponse(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            $filename
        );
    }

    /**
     * Test the view of the PDF
     *
     * @Route("/test/{id}", name="home_construct_projet_testpdf")
     */
    public function testPdfAction(Request $request,Projet $projet){
        $filename = sprintf('test-%s.pdf', date('Y-m-d'));

        return $this->render(
            '@HomeConstructBuild/PDF/layout-pdf.html.twig',[
                'projet' => $projet,
                'apercuPdf'=>true
            ]
        );
    }

    /**
     * Test the view of a mail
     *
     * @Route("/mail-test", name="home_construct_projet_mailtest")
     */
    public function testMailAction(Request $request){

        $symracineMail=array(
            'sender_name'=>"developpeur@home-construct.online",
            'base_route'=>'home_construct_accueil',
            'symracine_logo'=>'fas fa-user'
        );
        return $this->render(
            '@HomeConstructUser/Mail/new_account.html.twig',[
                'user' => $this->getUser(),
                'symracine_mail'=>$symracineMail
            ]
        );
    }

    /**
     * @Route("/{id}/liste-personnes-liés", name="home_construct_projet_list_users")
     */
    public function listUsersAction(Request $request,Projet $projet)
    {
        $entities=$projet->getUsers();

        for ($i = 0; $i < sizeof($entities); $i++) {
            if ($entities[$i]->getUsername()==$this->getUser()->getUsername()){
                unset ($entities[$i]);
            }
        }

        $page=array(
            'title'=>'Informations',
            'sub_title'=>'Liste des personnes liés au projet'
        );
        $object='Users';
        $titreOnglet='Liste personnes';

        return $this->render('@HomeConstructUser/Users/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'object' => $object,
            'titreOnglet'=>$titreOnglet,
            'projet'=>$projet
        ));
    }

    /**
     * @Route("/{id}/formulaire-ajout-personne", name="home_construct_projet_form_ajout_personne")
     */
    public function formAjoutUserAction(Request $request,Projet $projet){
        //$user=new Users();
        //$form = $this->createForm(EmailForLinkToProjectType::class, $user);
        /*if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            $email=$user->getEmail();
            $user=$this->getDoctrine()->getManager()
                ->getRepository('HomeConstructUserBundle:Users')
                ->findOneBy(['username'=>$email]);
            if($user){
                if(!in_array($user,$projet->getUsers()->toArray())){
                    $projet->addUser($user);
                    $user->addProjet($projet);
                    $em=$this->getDoctrine()->getManager();
                    $em->persist($user);
                    $em->persist($projet);
                    $em->flush();
                    // on envoie un mail
                    $this->get('symracine_mail.mailer')
                        ->sendMail('HomeConstructBuildBundle:Mail:added_to_project',
                            ['user' => $user,'token' => $user->getConfirmationToken(),'inviteur'=>$this->getUser(),'projet'=>$projet],
                            $user->getEmail()
                        );
                    $notif="Un email a été envoyé à la personne renseigné";
                    $request->getSession()->getFlashBag()->add('notice', $notif);
                    $request->getSession()->getFlashBag()->add('notif', 'True');
                    return $this->redirectToRoute('home_construct_projet_profile',[
                        'id'=>$projet->getId()
                    ]);
                }else{
                    $erreur="L'utilisateur renseigné est déjà lié à ce projet";
                    $request->getSession()->getFlashBag()->add('messageError', $erreur);
                    $request->getSession()->getFlashBag()->add('error', 'True');
                    return $this->redirect($request->headers->get('referer'));
                }
            }else{
                $erreur="L'email renseigné n'est pas lié à un utilisateur de Home-Construct";
                $request->getSession()->getFlashBag()->add('messageError', $erreur);
                $request->getSession()->getFlashBag()->add('error', 'True');
                return $this->redirect($request->headers->get('referer'));
            }
        }*/
        $page=array(
            'title'=>'Modification',
            'sub_title'=>'Ajout d\'une personne au projet'
        );

        return $this->render('@HomeConstructBuild/Projet/add_user.html.twig', array(
            'projet'=>$projet,
            'page'=>$page
        ));
    }

    /**
     * @Route("/{id}/ajout-personne-lie/{email}", name="home_construct_projet_ajout_personne")
     */
    public function ajoutUserAction(Request $request,Projet $projet,$email)
    {
        $user=$this->getDoctrine()->getManager()
            ->getRepository('HomeConstructUserBundle:Users')
            ->findOneBy(['username'=>$email]);
        if($user){
            if(!in_array($user,$projet->getUsers()->toArray())){
                $projet->addUser($user);
                $user->addProjet($projet);
                $em=$this->getDoctrine()->getManager();
                $em->persist($user);
                $em->persist($projet);
                $em->flush();
                // on envoie un mail
                $this->get('symracine_mail.mailer')
                    ->sendMail('HomeConstructBuildBundle:Mail:added_to_project',
                        ['user' => $user,'token' => $user->getConfirmationToken(),'inviteur'=>$this->getUser(),'projet'=>$projet],
                        $user->getEmail()
                    );
                $notif="Un email a été envoyé à la personne renseigné";
                $request->getSession()->getFlashBag()->add('notice', $notif);
                $request->getSession()->getFlashBag()->add('notif', 'True');
                return $this->redirectToRoute('home_construct_projet_list_users',[
                    'id'=>$projet->getId()
                ]);
            }else{
                $erreur="L'utilisateur renseigné est déjà lié à ce projet";
                $request->getSession()->getFlashBag()->add('messageError', $erreur);
                $request->getSession()->getFlashBag()->add('error', 'True');
                return $this->redirectToRoute('home_construct_projet_form_ajout_personne',[
                    'id'=>$projet->getId()
                ]);
            }

        }else{
            $erreur="L'email renseigné n'est pas lié à un utilisateur de Home-Construct";
            $request->getSession()->getFlashBag()->add('messageError', $erreur);
            $request->getSession()->getFlashBag()->add('error', 'True');
            return $this->redirectToRoute('home_construct_projet_form_ajout_personne',[
                'id'=>$projet->getId()
            ]);
        }
    }
    /**
     * @Route("/{idProjet}/suppression-personne-lie/{idUsers}", name="home_construct_projet_suppr_personne")
     * @ParamConverter("projet", options={"mapping": {"idProjet": "id"}})
     * @ParamConverter("users", options={"mapping": {"idUsers": "id"}})
     */
    public function removeUserAction(Request $request,Projet $projet,Users $users)
    {
        if(!($this->getUser()==$projet->getCreateur()) or !($this->getUser()->hasGroup('SUPER ADMIN')) or ($users==$projet->getCreateur())  ){
            $page = array(
                'title' => 'Suppression d\'un utilisateur d\'un projet',
                'sub_title'=>'Erreur d\'accès'
            );
            return $this->render('@HomeConstructBuild/Error/error_access.html.twig',[
                'page'=>$page
            ]);
        }
        $projet->removeUser($users);
        $users->removeProjet($projet);
        $em=$this->getDoctrine()->getManager();
        $em->persist($projet);
        $em->persist($users);
        $em->flush();

        $notif="L'utilisateur ".$users->getUsername()." a été supprimé du projet \"".$projet->getNom()."\"";
        $request->getSession()->getFlashBag()->add('notice', $notif);
        $request->getSession()->getFlashBag()->add('notif', 'True');
        return $this->redirect($request->headers->get('referer'));
    }


}

