<?php

namespace App\Controller;

use App\Entity\Modules;
use App\Entity\Session;
use App\Entity\Programme;
use App\Entity\Stagiaire;

use App\Form\SessionType;

use App\Repository\SessionRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SessionController extends AbstractController
{
    // Liste des sessions
    
    #[Route('/session', name: 'app_session')]
    public function index(ManagerRegistry $doctrine, SessionRepository $sr): Response
    {   
        $sessionsPassees= $sr->findSessionsPassees();
        $sessionsEnCours= $sr->findSessionsEnCours();
        $sessionsAVenir= $sr->findSessionsAVenir();

        $sessions = $doctrine->getRepository(Session::Class)->findBy([], ["dateDebut"=>"ASC"]);
        return $this->render('session/index.html.twig', [
            'sessions' => $sessions,
            'sessionsPassees' => $sessionsPassees,
            'sessionsEnCours' => $sessionsEnCours,
            'sessionsAVenir' => $sessionsAVenir

        ]);
    }

     // Ajouter ou editer une session
     #[Route('admin/session/add', name: 'add_session')]
     #[Route('admin/session/{id}/edit', name: 'edit_session')]
 
     public function add(ManagerRegistry $doctrine, Session $session = null, Request $request) : Response
     {
 
         if(!$session){
             $session = new Session();
         }
 
         // Creation du formulaire et objet qu'on lui fait passer
         $form = $this->createForm(SessionType::class, $session);
 
         //Récuperation des données du formulaire
         $form->handleRequest($request);
 
         // Vérification si le form est soumis et si les données sont saines
         if($form->isSubmitted() && $form->isValid())
         {   
             // On hydrate l'objet Session qu'on a crée avec les données du formulaire
             $session = $form->getData();
             // On récupère l'entity Manager pour avoir acces a persist et flush
             $entityManager = $doctrine->getManager();
             // On prépare l'objet
             $entityManager->persist($session);
             // On l'execute
             $entityManager->flush();
 
             return $this->redirectToRoute('app_session');
         }
 
         // Vue qui vas afficher le formulaire
         return $this->render('session/add.html.twig', [
             //generer le formulaire visuellement quand on utilise formAddSession
             'formAddSession' => $form->createView()
             
         ]);
     }

    // Afficher les details d'une session
    #[Route('session/{id}', name: 'detail_session')]
    public function detail(Session $session = null , SessionRepository $sr, ManagerRegistry $doctrine, $id ): Response

    {   
        if ($session){
            // Utilisation des fonctions des repository
            $nonInscrits = $sr->findNonInscrits($id);
            $nonProgrammes = $sr->findNonProgrammes($id);
    
            return $this->render('session/detail.html.twig', [
                'session' => $session,
                'nonInscrits' => $nonInscrits,
                'nonProgrammes' => $nonProgrammes
            ]);
        }
        else{
            return $this->redirectToRoute('app_session');
        }
    }

     //Désinscrire un stagiaire d'une session
     #[Route('admin/session/{idsession}/{id}/unsubscribe', name: 'unsubscribe_stagiaire')]
     public function unsubscribe(ManagerRegistry $doctrine, Stagiaire $stagiaire = null, $idsession): Response
     {  
        
        if ($stagiaire){

        $entityManager = $doctrine->getManager();
        $session = $entityManager->getRepository(Session::class)->find($idsession);

            if ($session)
            {
            $session->removeStagiaire($stagiaire);
            $entityManager->flush();
            
            return $this->redirectToRoute('detail_session',['id' => $idsession]);
            }
            else {
                return $this->redirectToRoute('app_session');
            }
        }
        else{
            return $this->redirectToRoute('app_session');
        }
     }

     //Inscrire un stagiaire a une session
     #[Route('admin/session/{idsession}/{id}/subscribe', name: 'subscribe_stagiaire')]
     public function subscribe(ManagerRegistry $doctrine, Stagiaire $stagiaire = null , $idsession): Response
     {  
            $entityManager = $doctrine->getManager();
            $session = $entityManager->getRepository(Session::class)->find($idsession);

            if ($stagiaire && $session->getNbPlace() > count($session->getStagiaires()))
            {
            $session->addStagiaire($stagiaire);
            $entityManager->flush();
            
            return $this->redirectToRoute('detail_session',['id' => $idsession]);
            }
            else
            {
                return $this->redirectToRoute('app_session');
            }

    }

    //Enlever un module d'une session
    #[Route('admin/session/{idsession}/{id}/removeModule', name: 'remove_module')]
    public function removeModule(ManagerRegistry $doctrine, Programme $programme = null, $idsession): Response
    {  
        if ($programme)
        {
            $entityManager = $doctrine->getManager();
            $session = $entityManager->getRepository(Session::class)->findOneBy(['id' => $idsession]);

            if ($session)
            {
            $session->removeProgramme($programme);
            $entityManager->flush();
            
            return $this->redirectToRoute('detail_session',['id' => $idsession]);
            }
            else 
            {
                return $this->redirectToRoute('app_session');
            }
        }
        else{
            return $this->redirectToRoute('app_session');
        }
    }

    //Ajouter un module a la session
    #[Route('admin/session/{idsession}/{id}/addModule', name: 'add_module')]
    public function addModule(ManagerRegistry $doctrine, Modules $module = null, $idsession, $id): Response
    {  
       $entityManager = $doctrine->getManager();

            if ($module)
            {

                if (isset($_POST['submit']))
                {   
                    $duree = filter_input(INPUT_POST, "duree", FILTER_VALIDATE_INT);

                    //Récupération de la session correspondant a l'id
                    $session = $entityManager->getRepository(Session::class)->findOneBy(['id' => $idsession]);

                    if($duree && $session)
                    {
                        //Récupération du module correspondant a l'id
                        $module = $entityManager->getRepository(Modules::class)->findOneBy(['id' => $id]);

                        // Création du nouvel objet programme
                        $programme = new Programme();

                        //Définitions des champs grâce aux setters
                        $programme->setModules($module);
                        $programme->setDuree($duree);
                        $programme->setSessions($session);

                        // Ajout du programme
                        $session->addProgramme($programme);

                        // Préparation et ajout dans la BDD
                        $entityManager->persist($programme);
                        $entityManager->flush();

                        return $this->redirectToRoute('detail_session',['id' => $idsession]);
                    }
                    else{
                        return $this->redirectToRoute('app_session');
                    }
                }
            }
            else {
                return $this->redirectToRoute('app_session');
            }
       
    }

    // Supprimer une session
    #[Route('admin/session/{id}/delete', name: 'delete_session')]
    public function delete(ManagerRegistry $doctrine, Session $session = null): Response
    {
        if ($session){

        $entityManager = $doctrine->getManager();
        $entityManager->remove($session);
        $entityManager->flush();

        return $this->redirectToRoute('app_session');
        }
        else{
            return $this->redirectToRoute('app_session');
        }
    }
   
}
