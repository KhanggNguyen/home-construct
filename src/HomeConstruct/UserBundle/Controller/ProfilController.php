<?php

namespace HomeConstruct\UserBundle\Controller;

use HomeConstruct\UserBundle\Entity\Users;
use HomeConstruct\UserBundle\Entity\Groupe;
use HomeConstruct\UserBundle\Form\Type\UsersType;
use HomeConstruct\UserBundle\Form\Type\UsersUpdateByUsersType;
use HomeConstruct\UserBundle\Form\Type\UsersBySuperAdminType;
use HomeConstruct\UserBundle\Form\UsersGroupeBySuperAdminType;
use HomeConstruct\UserBundle\Form\UserSuperAdminType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Profil controller.
 *
 * @Route("/utilisateur")
 */
class ProfilController extends Controller
{
    /**
     * @Route("/", name="home_construct_user_accueil")
     */
    public function indexAction()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('@HomeConstructUser/Default/index.html.twig');
    }

    /**
     * @Route("/mon-profil/", name="home_construct_user_myprofile_show")
     */
    public function myProfileAction(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $page = array(
            'title' => 'Utilisateur',
            'sub_title' => 'Mon profil'
        );
        $titreOnglet="Mon profil";
        $user = $this->getUser();


        return $this->render('@HomeConstructUser/Users/profile_user_view.html.twig', [
            'user' => $user,
            'page' => $page,
            'titreOnglet'=>$titreOnglet
        ]);
    }

    /**
     * @Route("/{id}/profil", name="home_construct_user_profile_show")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function profileAction(Request $request, $id)
    {
        $page = array(
            'title' => 'Utilisateur',
            'sub_title' => 'Profil de l\'utilisateur'
        );
        $titreOnglet="Profil Utilisateur";
        $userToEdit = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('HomeConstructUserBundle:Users')
            ->find($id);
        $user = $this->getUser();
        $groupes = $userToEdit->getGroups();

            if ($userToEdit->hasGroup('SUPER ADMIN')) {
                return $this->redirectToRoute('home_construct_user_list');
            } else {
                return $this->render('@HomeConstructUser/Users/profile_user_view.html.twig', [
                    'user' => $userToEdit,
                    'page' => $page,
                    'titreOnglet'=>$titreOnglet
                ]);
            }

    }

    /**
     * @Route("/mon-profil/modification/vue", name="home_construct_user_myprofile_edit_general")
     */
    public function myProfileEditGeneralAction(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $page = array(
            'title' => 'Utilisateur',
            'sub_title' => 'Modifier votre profil'
        );

        $titreOnglet="Modification mon profil";

        $user = $this->getUser();

        $tabgroupe=$user->getGroups();
        $groupe=$tabgroupe[0];

        $editForm = $this->createForm(UsersUpdateByUsersType::class, $user,['groupe'=>$groupe]);
        if ($request->isMethod('POST') && $editForm->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
            $notif="Votre profil a bien été modifié";
            return $this->redirectToRoute('home_construct_user_myprofile_show_notif', ['notif'=>$notif]);
        }

        $deleteForm = $this->createDeleteForm('home_construct_user_myuser_delete');

        return $this->render('@HomeConstructUser/Users/profile_user_edit_general.html.twig', [
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'page' => $page,
            'user' => $user,
            'titreOnglet'=>$titreOnglet
        ]);
    }



    /**
     * @Route("/mon-profil/modification/suppression", name="home_construct_user_myuser_delete")
     */
    public function deleteMyUserAction(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $form = $this->createDeleteForm('home_construct_user_myuser_delete');

        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $projetsCrees=$user->getProjetsCrees();
            $em = $this->getDoctrine()->getManager();
            if($projetsCrees){
                foreach ($projetsCrees as $projetCree){
                    $projetCree->setCreateur(null);
                    $em->persist($projetCree);
                }
                $em->flush();
            }
            $em->remove($user);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('home_construct_accueil'));
    }

    /**
     * @Route("/{id}/suppression", name="home_construct_user_delete_direct")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function deleteDirectAction(Request $request, $id)
    {
        $userSupprime = $this
        ->getDoctrine()
        ->getManager()
        ->getRepository('HomeConstructUserBundle:Users')
        ->find($id);

        $userConnecte=$this->getUser();

            if ($userSupprime->hasGroup('SUPER ADMIN')) {
                return $this->redirectToRoute('home_construct_user_list');
            } else {
                $projetsCrees=$userSupprime->getProjetsCrees();
                $em = $this->getDoctrine()->getManager();
                if($projetsCrees){
                    foreach ($projetsCrees as $projetCree){
                        $projetCree->setCreateur(null);
                        $em->persist($projetCree);
                    }
                    $em->flush();
                }
                $em->remove($userSupprime);
                $em->flush();
                $notif="Utilisateur ".$userSupprime->getName()." ".$userSupprime->getFirstname()." supprimé";
                $request->getSession()->getFlashBag()->add('notice', $notif);
                $request->getSession()->getFlashBag()->add('notif', 'True');
                return $this->redirectToRoute('home_construct_user_list');
            }
    }


    /**
     * @Route("/{id}/modification/suppression", name="home_construct_user_delete_profile")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function deleteUserAction(Request $request, $id)
    {
        $userSupprime = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('HomeConstructUserBundle:Users')
            ->find($id);

        $userConnecte=$this->getUser();
            if ($userSupprime->hasGroup('SUPER ADMIN')) {
                return $this->redirectToRoute('home_construct_user_list');
            } else {
                $form = $this->myCreateDeleteForm('home_construct_user_delete_profile', $id);

                if ($form->handleRequest($request)->isValid()) {
                    $em = $this->getDoctrine()->getManager();
                    $em->remove($userSupprime);
                    $em->flush();
                }

                return $this->redirectToRoute('home_construct_user_accueil');
            }
    }

    /**
     * Create Delete form
     *
     * @param string $route
     * @return \Symfony\Component\Form\Form
     */
    protected function createDeleteForm($route)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->createFormBuilder(null, array('attr' => array('id' => 'delete')))
            ->setAction($this->generateUrl($route))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Create Delete form
     *
     * @param string $route
     * @return \Symfony\Component\Form\Form
     */
    protected function myCreateDeleteForm($route, $id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->createFormBuilder(null, array('attr' => array('id' => 'delete')))
            ->setAction($this->generateUrl($route, array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * @Route("/liste", name="home_construct_user_list")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function listAction(Request $request, $notif=null)
    {
        $page = array(
            'title' => 'Utilisateurs',
            'sub_title' => 'Liste des utilisateurs'
        );
        $titreOnglet="Utilisateurs";
        $userCourant = $this->getUser();
        $object = array(
            'name' => 'Utilisateur'
        );
        $entities = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('HomeConstructUserBundle:Users')
            ->findAll();

        for ($i = 0; $i < sizeof($entities); $i++) {
            if ($entities[$i]->getUsername()==$userCourant->getUsername()){
                unset ($entities[$i]);
                $index=$i;
            }
        }
        return $this->render('@HomeConstructUser/Users/list.html.twig', array(
            'page' => $page,
            'entities' => $entities,
            'object' => $object,
            'titreOnglet'=>$titreOnglet,
        ));
    }

    /**
     * @Route("/creation/", name="home_construct_user_create")
     */
    public function createUser(Request $request)
    {   
        $page = array(
            'title' => 'Création',
            'sub_title' => 'Création d\'un nouvel utilisateur'
        );

        $titreOnglet="Création Utilisateur";

        $groupeSuperAdmin = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('HomeConstructUserBundle:Groupe')
            ->findOneBy(['name' => 'SUPER ADMIN']);
        $groupeProfessionnel=$this
            ->getDoctrine()
            ->getManager()
            ->getRepository('HomeConstructUserBundle:Groupe')
            ->findOneBy(['name' => 'PROFESSIONNEL']);
        // on crée un nouvel Users
        $user = new Users();
        $form = $this->createForm(UsersType::class, $user, ['groupeSuperAdmin'=>$groupeSuperAdmin,'groupeProfessionnel'=>$groupeProfessionnel]);

        // si la méthode est POST et si le formulaire est valide
        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            // l'utilisateur crée aura de base un mot de passe généré aléatoirement
            //$mdp = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);
            // on met la premiere lettre du nom ou du prenom en majuscule et le reste en minuscule
            $user->setName(ucfirst(strtolower($user->getName())));
            $user->setFirstname(ucfirst(strtolower($user->getFirstname())));
            // on met en minuscule l'adresse email rentré dans le formulaire
            $user->setEmail(strtolower($user->getEmail()));
            // on lui donne le mot de passe que l'on a crée aleatoirement
            //$user->setPassword($mdp);
            //$user->setPlainPassword($mdp);
            // on recupere le groupe selectionne puis on l'ajoute aux groupes de l'utilisateur
            //$group=$form->get('groups')->getData();
            $em=$this->getDoctrine()->getManager();
            $groupeClient=$em->getRepository('HomeConstructUserBundle:Groupe')
                ->findOneBy(['name'=>'CLIENT']);
            $user->addGroup($groupeClient);
            $user->setEnabled(false);

            // si le token de confirmation n'a pas encore été donné on lui en donne un
            if (null === $user->getConfirmationToken()) {
                $user->setConfirmationToken(substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 15));
            }

            $em->persist($user);
            $em->flush();
            // on envoie le mail de création de compte au user crée
            $this->get('symracine_mail.mailer')
                ->sendMail('HomeConstructUserBundle:Mail:new_account',
                    ['user' => $user,'token' => $user->getConfirmationToken()],
                    $user->getEmail()
                );
            $notifMail="Un email de confirmation a été envoyé sur l'adresse : ".$user->getEmail();
            $request->getSession()->getFlashBag()->add('notifMail', $notifMail);
            $request->getSession()->getFlashBag()->add('mailSend', 'True');
            // si la personne qui crée l'utilisateur est un super admin on le redirige vers la liste des users
            if(($this->getUser()) && $this->getUser()->hasGroup("SUPER ADMIN")){
                return $this->redirectToRoute('home_construct_user_list');
            }else{ // sinon on le redirige vers la page de login
                return $this->redirectToRoute('fos_user_security_login',[
                    'mail'=>$user->getEmail()]);
            }
        };
        // on redirige vers la vue de formulaire
        return $this->render('@HomeConstructUser/Users/create_user.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet'=>$titreOnglet
        ]);
    }

    /**
     * @Route("/ajout", name="home_construct_user_ajout")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function addUser(Request $request)
    {
        $page = array(
            'title' => 'Création',
            'sub_title' => 'Création d\'un nouvel utilisateur'
        );

        $titreOnglet="Création Utilisateur";

        // on récupère les groupes de l'utilisateur courant
        $groups=$this->getUser()->getGroups();

        $groupeSuperAdmin = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('HomeConstructUserBundle:Groupe')
            ->findOneBy(['name' => 'SUPER ADMIN']);
        // on crée un nouvel User
        $user = new Users();

        $form = $this->createForm(UserSuperAdminType::class, $user,['groupeSuperAdmin'=>$groupeSuperAdmin]);

        // si la méthode est POST et si le formulaire est valide
        if ($request->isMethod('POST') and $form->handleRequest($request)->isValid()) {
            // l'utilisateur crée aura de base un mot de passe généré aléatoirement
            $mdp = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);
            // on met la premiere lettre du nom ou du prenom en majuscule et le reste en minuscule
            $user->setName(ucfirst(strtolower($user->getName())));
            $user->setFirstname(ucfirst(strtolower($user->getFirstname())));
            // on met en minuscule l'adresse email rentré dans le formulaire
            $user->setEmail(strtolower($user->getEmail()));
            // on lui donne le mot de passe que l'on a crée aleatoirement
            $user->setPassword($mdp);
            $user->setPlainPassword($mdp);
            // on recupere le groupe selectionne puis on l'ajoute aux groupes de l'utilisateur
            $group=$form->get('groups')->getData();
            if($group->getName()=='PROFESSIONNEL'){
                $user->addRole('ROLE_ADMIN');
            }elseif($group->getName()=="SUPER ADMIN"){
                $user->addRole('ROLE_SUPER_ADMIN');
            }

            $user->addGroup($group);
            $user->setEnabled(false);

            // si le token de confirmation n'a pas encore été donné on lui en donne un
            if (null === $user->getConfirmationToken()) {
                $user->setConfirmationToken(substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 15));
            }

            $ep = $this->getDoctrine()->getManager();
            $ep->persist($user);
            $ep->flush();
            // on envoie le mail de création de compte au user crée
            $this->get('symracine_mail.mailer')
                ->sendMail('HomeConstructUserBundle:Mail:new_account_new_passwd',
                    ['user' => $user,'token' => $user->getConfirmationToken()],
                    $user->getEmail()
                );
            $notif="Utilisateur ".$user->getName()." ".$user->getFirstname()." crée";
            $request->getSession()->getFlashBag()->add('notice', $notif);
            $request->getSession()->getFlashBag()->add('notif', 'True');
            $notifMail="Email envoyé à ".$user->getEmail();
            $request->getSession()->getFlashBag()->add('notifMail', $notifMail);
            $request->getSession()->getFlashBag()->add('mailSend', 'True');
            return $this->redirectToRoute('home_construct_user_list');
        };
        // on redirige vers la vue de formulaire
        return $this->render('@HomeConstructUser/Users/add_user.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'titreOnglet'=>$titreOnglet
        ]);
    }
}
