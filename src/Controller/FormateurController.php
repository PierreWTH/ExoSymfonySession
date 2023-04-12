<?php

namespace App\Controller;

use App\Entity\Formateur;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FormateurController extends AbstractController
{   
    // Afficher la liste des formateurs 
    #[Route('/formateur', name: 'app_formateur')]
    public function index(ManagerRegistry $doctrine): Response
    {   
        // Recuperer tous les formateurs dans la BDD
        $formateurs = $doctrine->getRepository(Formateur::Class)->findBy([], ["nom"=>"ASC"]);
        return $this->render('formateur/index.html.twig', [
            'controller_name' => 'FormateurController',
            'formateurs' => $formateurs
        ]);
    }

    // Afficher les details d'un formateur
    #[Route('/formateur/{id}', name: 'detail_formateur')]
    public function detail(Formateur $formateur): Response
    {
        return $this->render('formateur/detail.html.twig', [
            'formateur' => $formateur
        ]);
    }
}