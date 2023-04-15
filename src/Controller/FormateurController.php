<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Formateur;
use App\Form\FormateurType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
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
     // Ajouter ou éditer un formateur
     #[Route('admin/formateur/add', name: 'add_formateur')]
     #[Route('admin/formateur/{id}/edit', name: 'edit_formateur')]
     public function add(ManagerRegistry $doctrine, Formateur $formateur = null, Request $request) : response
     {
        if(!$formateur){
            $formateur = new Formateur();
        }

         // Creation du formulaire et objet qu'on lui fait passer
         $form = $this->createForm(FormateurType::class, $formateur);
 
         //Récuperation des données du formulaire
         $form->handleRequest($request);
 
         // Vérification si le form est soumis et si les données sont saines
         if($form->isSubmitted() && $form->isValid())
         {   
             // On hydrate l'objet formateur qu'on a crée avec les données du formulaire
             $formateur = $form->getData();
             // On récupère l'entity Manager pour avoir acces a persist et flush
             $entityManager = $doctrine->getManager();
             // On prépare l'objet
             $entityManager->persist($formateur);
             // On l'execute
             $entityManager->flush();
 
             return $this->redirectToRoute('app_formateur');
         }
 
         // Vue qui vas afficher le formulaire
         return $this->render('formateur/add.html.twig', [
             //generer le formulaire visuellement quand on utilise formAddformateur
             'formAddFormateur' => $form->createView()
             
         ]);
     }

    // Afficher les details d'un formateur
    #[Route('/formateur/{id}', name: 'detail_formateur')]
    public function detail(ManagerRegistry $doctrine, Formateur $formateur): Response
    {   
        $entityManager = $doctrine->getManager();
        $sessions = $entityManager->getRepository(Session::class)->findAll();
        return $this->render('formateur/detail.html.twig', [
            'formateur' => $formateur,
            'sessions' => $sessions
        ]);
    }


    // Supprimer un formateur
    #[Route('admin/formateur/{id}/delete', name: 'delete_formateur')]
    public function delete(ManagerRegistry $doctrine, Formateur $formateur): Response
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($formateur);
        $entityManager->flush();

        return $this->redirectToRoute('app_formateur');
    }
}