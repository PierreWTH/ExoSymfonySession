<?php

namespace App\Controller;

use App\Entity\Modules;
use App\Entity\Session;
use App\Entity\Programme;
use App\Entity\Stagiaire;

use Doctrine\Persistence\ManagerRegistry;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SessionController extends AbstractController
{
    #[Route('/session', name: 'app_session')]
    public function index(): Response
    {
        return $this->render('session/index.html.twig', [
            'controller_name' => 'SessionController',
        ]);
    }

    // Afficher les details d'une session
    #[Route('/session/{id}', name: 'detail_session')]
    public function detail(Session $session, ManagerRegistry $doctrine, $id ): Response
    {   
        $stagiaires = $doctrine->getRepository(Stagiaire::Class)->findBy([], ["nom"=>"ASC"]);
        $modules = $doctrine->getRepository(Modules::Class)->findBy([], ["nomModule"=>"ASC"]);
        $modulesBySession = $doctrine->getRepository(Programme::Class)->findBy(["sessions"=> $id], []);

        return $this->render('session/detail.html.twig', [
            'session' => $session,
            'stagiaires' => $stagiaires,
            'modules'=>$modules,
            'modulesBySession'=>$modulesBySession,
        ]);
    }


     //Désinscrire un stagiaire d'une session
     #[Route('/session/{idsession}/{id}/unsubscribe', name: 'unsubscribe_stagiaire')]
     public function unsubscribe(ManagerRegistry $doctrine, Stagiaire $stagiaire, $idsession): Response
     {  
        $entityManager = $doctrine->getManager();
        $session = $entityManager->getRepository(Session::class)->find($idsession);
        $session->removeStagiaire($stagiaire);
        $entityManager->flush();
        
        return $this->redirectToRoute('detail_session',['id' => $idsession]);
     }

     //Inscrire un stagiaire a une session
     #[Route('/session/{idsession}/{id}/subscribe', name: 'subscribe_stagiaire')]
     public function subscribe(ManagerRegistry $doctrine, Stagiaire $stagiaire, $idsession): Response
     {  
        $entityManager = $doctrine->getManager();
        $session = $entityManager->getRepository(Session::class)->find($idsession);
        $session->addStagiaire($stagiaire);
        $entityManager->flush();
        
        return $this->redirectToRoute('detail_session',['id' => $idsession]);
     }

    //Enlever un module d'une session
    #[Route('/session/{idsession}/{id}/removeModule', name: 'remove_module')]
    public function removeModule(ManagerRegistry $doctrine, Programme $programme, $idsession): Response
    {  
       $entityManager = $doctrine->getManager();
       $session = $entityManager->getRepository(Session::class)->findOneBy(['id' => $idsession]);
       $session->removeProgramme($programme);
       $entityManager->flush();
       
       return $this->redirectToRoute('detail_session',['id' => $idsession]);
    }

    //Ajouter un module a la session
    #[Route('/session/{idsession}/{id}/addModule', name: 'add_module')]
    public function addModule(ManagerRegistry $doctrine, Modules $module, $idsession, $id): Response
    {  
       $entityManager = $doctrine->getManager();

       if (isset($_POST['submit']))
        { 
            $duree = filter_input(INPUT_POST, "duree", FILTER_VALIDATE_INT);

       //Récupération de la session correspondant a l'id
       $session = $entityManager->getRepository(Session::class)->findOneBy(['id' => $idsession]);

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

        }
       
       return $this->redirectToRoute('detail_session',['id' => $idsession]);
    }
}
