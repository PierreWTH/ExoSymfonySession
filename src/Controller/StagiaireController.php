<?php

namespace App\Controller;

use App\Entity\Stagiaire;
use App\Form\StagiaireType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StagiaireController extends AbstractController
{   
    // Lister tous les stagiaires
    #[Route('/stagiaire', name: 'app_stagiaire')]
    public function index(ManagerRegistry $doctrine): Response
    {

        // Recupere les stagiaires de la BDD

        $stagiaires = $doctrine->getRepository(Stagiaire::Class)->findBy([], ["nom"=>"ASC"]);
        return $this->render('stagiaire/index.html.twig', [
            'controller_name' => 'StagiaireController',
            'stagiaires' => $stagiaires
        ]);
    }

    // Ajouter un stagiaire
    #[Route('/stagiaire/add', name: 'add_stagiaire')]
    public function add(ManagerRegistry $doctrine, Stagiaire $stagiaire = null, Request $request) : response
    {
        // Creation du formulaire et objet qu'on lui fait passer
        $form = $this->createForm(StagiaireType::class, $stagiaire);

        //Récuperation des données du formulaire
        $form->handleRequest($request);

        // Vérification si le form est soumis et si les données sont saines
        if($form->isSubmitted() && $form->isValid())
        {   
            // On hydrate l'objet stagiaire qu'on a crée avec les données du formulaire
            $stagiaire = $form->getData();
            // On récupère l'entity Manager pour avoir acces a persist et flush
            $entityManager = $doctrine->getManager();
            // On prépare l'objet
            $entityManager->persist($stagiaire);
            // On l'execute
            $entityManager->flush();

            return $this->redirectToRoute('app_stagiaire');
        }

        // Vue qui vas afficher le formulaire
        return $this->render('stagiaire/add.html.twig', [
            //generer le formulaire visuellement quand on utilise formAddStagiaire
            'formAddStagiaire' => $form->createView()
            
        ]);
    }

    // Afficher les details d'un stagiaire
    #[Route('/stagiaire/{id}', name: 'detail_stagiaire')]
    public function detail(Stagiaire $stagiaire): Response
    {
        return $this->render('stagiaire/detail.html.twig', [
            'stagiaire' => $stagiaire
        ]);
    }
}
