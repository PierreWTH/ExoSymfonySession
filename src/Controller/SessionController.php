<?php

namespace App\Controller;

use App\Entity\Modules;
use App\Entity\Session;
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
    public function detail(Session $session, ManagerRegistry $doctrine): Response
    {   
        $stagiaires = $doctrine->getRepository(Stagiaire::Class)->findBy([], ["nom"=>"ASC"]);
        $modules = $doctrine->getRepository(Modules::Class)->findBy([], ["nomModule"=>"ASC"]);

        return $this->render('session/detail.html.twig', [
            'session' => $session,
            'stagiaires' => $stagiaires,
            'modules'=>$modules
        ]);
    }
}
