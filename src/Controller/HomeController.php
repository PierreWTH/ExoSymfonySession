<?php

namespace App\Controller;

use App\Entity\Session;
use App\Repository\SessionRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{   
    // Route vers page d'acceuil avec l'affichage des sessions
    #[Route('/home', name: 'app_home')]
    public function index(ManagerRegistry $doctrine, SessionRepository $sr): Response
    {
        $sessionsPassees= $sr->findSessionsPassees();
        $sessionsEnCours= $sr->findSessionsEnCours();
        $sessionsAVenir= $sr->findSessionsAVenir();

        $sessions = $doctrine->getRepository(Session::Class)->findBy([], ["dateDebut"=>"ASC"]);
        return $this->render('home/index.html.twig', [
            'sessions' => $sessions,
            'sessionsPassees' => $sessionsPassees,
            'sessionsEnCours' => $sessionsEnCours,
            'sessionsAVenir' => $sessionsAVenir

        ]);
    }

    // Route vers la page profil
    #[Route('/profil', name: 'app_profil')]
    public function viewProfil(): Response
    {
        return $this->render('home/profil.html.twig', []);
    }
}
