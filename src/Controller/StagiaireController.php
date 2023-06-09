<?php

namespace App\Controller;

use App\Entity\Session;
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

    // Ajouter ou editer un stagiaire
    #[Route('admin/stagiaire/add', name: 'add_stagiaire')]
    #[Route('admin/stagiaire/{id}/edit', name: 'edit_stagiaire')]

    public function add(ManagerRegistry $doctrine, Stagiaire $stagiaire = null, Request $request) : response
    {

        if(!$stagiaire){
            $stagiaire = new Stagiaire();
        }

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
    public function detail(ManagerRegistry $doctrine, Stagiaire $stagiaire = null): Response
    {   

        if ($stagiaire)
        {
        $entityManager = $doctrine->getManager();
        $sessions = $entityManager->getRepository(Session::class)->findAll();
        return $this->render('stagiaire/detail.html.twig', [
            'stagiaire' => $stagiaire,
            'sessions' => $sessions
        ]);
        }
        else
        {
            return $this->redirectToRoute('app_stagiaire'); 
        }
    }

    // Supprimer un stagiaire
    #[Route('admin/stagiaire/{id}/delete', name: 'delete_stagiaire')]
    public function delete(ManagerRegistry $doctrine, Stagiaire $stagiaire = null): Response
    {   
        if ($stagiaire){
        $entityManager = $doctrine->getManager();
        $entityManager->remove($stagiaire);
        $entityManager->flush();

        return $this->redirectToRoute('app_stagiaire');
        }
        else
        {
            return $this->redirectToRoute('app_stagiaire');   
        }
    }

}
