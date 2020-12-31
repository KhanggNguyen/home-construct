<?php
/**
 * Created by PhpStorm.
 * User: kweidner
 * Date: 30/03/2019
 * Time: 19:13
 */

namespace HomeConstruct\BuildBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class PathHelper
{
    /**
     *
     * @var EntityManager
     */
    protected $em;

    protected $request;

    public function __construct(EntityManagerInterface $entityManager=null, Request $request=null)
    {
        $this->em = $entityManager;
        $this->request=$request;
    }

    public function pathIsAForm($path){
        if((strpos($path,'edit') !== false) or (strpos($path,'creation') !== false)) {
            return true;
        }else{
            return false;
        }
    }

    public function pathIsProfilePiece($path){
        if((strpos($path,'piece/profile') !== false)) {
            return true;
        }else{
            return false;
        }
    }

    public function pathIsEntityLinkedPiece($path){
        if((strpos($path,'chauffage') !== false ) or (strpos($path,'ventilation') !== false) or (strpos($path,'climatisation') !== false) or (strpos($path,'revetement-sol') !== false)) {
            return true;
        }else{
            return false;
        }
    }

    public function pathIsADeleteAction($path){
        if((strpos($path,'suppression') !== false)) {
            return true;
        }else{
            return false;
        }
    }

    public function calculPrixForLinkedEntitiesGo($entityDeleted,$idProjet){
        $toitureNom='toiture';
        $charpenteNom='charpente';
        $menuiserieExterieureNom='menuiserie-exterieure';
        $etudeSolNom='etude-sol';
        $excavationNom='excavation';
        $prepaAccesTerrainNom='prepa-acces-terrain';
        $fondationNom='fondation';
        $soubassementNom='soubassement';
        $vrdNom='vrd';
        $plancherNom='plancher';
        $elevationNom='elevation';

        $em=$this->em;
        $projet=$em->getRepository('HomeConstructBuildBundle:Projet')->find($idProjet);

        if($entityDeleted == $etudeSolNom or $entityDeleted == $soubassementNom){
            if($projet->getGrosOeuvre()->getFondation()){
                $fondation=$projet->getGrosOeuvre()->getFondation();
                $fondation->calculPrix();
                $em->persist($fondation);
                $em->flush();
            }
        }
    }
    public function calculPrixForLinkedEntitiesSo($entityDeleted,$idProjet){

    }

    public function calculPrixGoAndProjet($grosOeuvre){
        $em=$this->em;
        $grosOeuvre->calculPrix();
        $em->persist($grosOeuvre);
        $em->flush();
        $projet=$grosOeuvre->getProjet();
        $projet->calculPrix();
        $em->flush();
    }

    public function calculPrixSoAndProjet($secondOeuvre){
        $em=$this->em;
        $secondOeuvre->calculPrix();
        $em->persist($secondOeuvre);
        $em->flush();
        $projet=$secondOeuvre->getProjet();
        $projet->calculPrix();
        $em->flush();
    }

    public function calculPrixAfterEditGo($tab=null,$grosOeuvre){
        $em=$this->em;
        $request=$this->request;
        if($tab) {
            $i=1;
            foreach ($tab as $entity) {
                //si l'entité n'est pas null (si elle existe)
                if($entity){
                    // on fait un switch sur le nom de l'entité en paramètre (méthode ajouté aux entités)
                    switch ($entity->getEntityName()) {
                        case 'Soubassement':
                            $prixAvantCalcul = $entity->getPrixTotal();
                            $entity->calculPrix();
                            $em->persist($entity);
                            $grosOeuvre->calculPrix();
                            $em->persist($grosOeuvre);
                            $prixApresCalcul = $entity->getPrixTotal();
                            break;
                        case 'Fondation':
                            $prixAvantCalcul = $entity->getPrix();
                            $entity->calculPrix();
                            $em->persist($entity);
                            $grosOeuvre->calculPrix();
                            $em->persist($grosOeuvre);
                            $prixApresCalcul = $entity->getPrix();
                            break;
                        case 'Etude Sol':
                            $prixAvantCalcul = $entity->getPrixForfait();
                            $entity->calculPrix();
                            $em->persist($entity);
                            $grosOeuvre->calculPrix();
                            $em->persist($grosOeuvre);
                            $prixApresCalcul = $entity->getPrixForfait();
                            break;
                        case 'Charpente':
                            $prixAvantCalcul = $entity->getPrix();
                            $entity->calculPrix();
                            $em->persist($entity);
                            $grosOeuvre->calculPrix();
                            $em->persist($grosOeuvre);
                            $prixApresCalcul = $entity->getPrix();
                            break;
                        default:
                            $prixApresCalcul = $prixAvantCalcul = null;
                    }
                    if ($prixApresCalcul != $prixAvantCalcul) {
                        $tab = [
                            'prixAvantCalcul' => $prixAvantCalcul,
                            'prixApresCalcul' => $prixApresCalcul
                        ];
                        $request->getSession()->getFlashBag()->add('newPrice'.$i, 'Le prix total est passé de ' . $tab['prixAvantCalcul'] . '€ à ' . $tab['prixApresCalcul'] . '€');
                        $request->getSession()->getFlashBag()->add('nameEntity'.$i, $entity->getEntityName());
                        $request->getSession()->getFlashBag()->add('calcul'.$i, 'True');
                    }
                    $i++;
                }else{
                    $grosOeuvre->calculPrix();
                    $em->persist($grosOeuvre);
                }
            }
        }else{
            return null;
        }
    }

    public function showNotifPriceGrosOeuvre($lastPrice,$newPrice){
        if($lastPrice!=$newPrice) {
            $request = $this->request;
            $request->getSession()->getFlashBag()->add('newPriceGO', 'Le prix total est passé de ' . $lastPrice . '€ à ' . $newPrice . '€');
            $request->getSession()->getFlashBag()->add('nameEntityGO', 'Gros Oeuvre');
            $request->getSession()->getFlashBag()->add('calculGO', 'True');
        }
    }

    public function showNotifPriceSecondOeuvre($lastPrice,$newPrice){
        if($lastPrice!=$newPrice) {
            $request = $this->request;
            $request->getSession()->getFlashBag()->add('newPriceSO', 'Le prix total est passé de ' . $lastPrice . '€ à ' . $newPrice . '€');
            $request->getSession()->getFlashBag()->add('nameEntitySO', 'Second Oeuvre');
            $request->getSession()->getFlashBag()->add('calculSO', 'True');
        }
    }

    public function showNotifPriceChange($entity,$lastPrice,$newPrice){
        if($lastPrice!=$newPrice){
            $request=$this->request;
            $request->getSession()->getFlashBag()->add('newPrice3', 'Le prix total est passé de ' . $lastPrice . '€ à ' . $newPrice . '€');
            $request->getSession()->getFlashBag()->add('nameEntity3', $entity->getEntityName());
            $request->getSession()->getFlashBag()->add('calcul3', 'True');
        }
    }

    public function sendMailToClientsWhenEdit($mailer,$thisUser,$oeuvre,$lastPriceOeuvre,$newPriceOeuvre,$creation,$templateMail){
        // on envoie un mail a tous les clients du projet pour dire que l'étude de sol a été ajouté
        $usersProjets = $oeuvre->getProjet()->getUsers();
        if ($usersProjets) {
            $clientsProjets = array();
            $i = 0;
            foreach ($usersProjets as $userProjet) {
                if ($userProjet->hasGroup('CLIENT')) {
                    $clientsProjets[$i] = $userProjet;
                    $i++;
                }
            }
            if ($clientsProjets) {
                foreach ($clientsProjets as $client) {
                    $mailer
                        ->sendMail($templateMail,
                            ['client' => $client,
                                'user' => $thisUser,
                                'creation' => $creation,
                                'projet' => $oeuvre->getProjet(),
                                'prixGOApresCalcul'=>$newPriceOeuvre,
                                'prixGOAvantCalcul'=>$lastPriceOeuvre
                            ],
                            $client->getEmail()
                        );
                }
                $request=$this->request;
                $notifMail = "Email(s) de notification envoyé(s) aux clients reliés au projet";
                $request->getSession()->getFlashBag()->add('notifMail', $notifMail);
                $request->getSession()->getFlashBag()->add('mailSend', 'True');
            }
        }
    }

    public function sendMailToClientsWhenDelete($mailer,$thisUser,$oeuvre,$templateMail){
        // on envoie un mail a tous les clients du projet pour dire que l'étude de sol a été ajouté
        $usersProjets = $oeuvre->getProjet()->getUsers();
        if ($usersProjets) {
            $clientsProjets = array();
            $i = 0;
            foreach ($usersProjets as $userProjet) {
                if ($userProjet->hasGroup('CLIENT')) {
                    $clientsProjets[$i] = $userProjet;
                    $i++;
                }
            }
            if ($clientsProjets) {
                foreach ($clientsProjets as $client) {
                    $mailer
                        ->sendMail($templateMail,
                            ['client' => $client,
                                'user' => $thisUser,
                                'projet' => $oeuvre->getProjet()
                            ],
                            $client->getEmail()
                        );
                }
                $request=$this->request;
                $notifMail = "Email(s) de notification envoyé(s) aux clients reliés au projet";
                $request->getSession()->getFlashBag()->add('notifMail', $notifMail);
                $request->getSession()->getFlashBag()->add('mailSend', 'True');
            }
        }
    }


    }
