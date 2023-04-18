<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FormationController extends AbstractController
{   
    // Liste des formations
    #[Route('/formation', name: 'app_formation')]
    public function index(ManagerRegistry $doctrine): Response
    {   
        $formations = $doctrine->getRepository(Formation::Class)->findBy([], ["nomFormation"=>"ASC"]);
        return $this->render('formation/index.html.twig', [
            'formations' => $formations,
            
        ]);
    }

     // Ajouter ou editer une formation
     #[Route('admin/formation/add', name: 'add_formation')]
     #[Route('admin/formation/{id}/edit', name: 'edit_formation')]
 
     public function add(ManagerRegistry $doctrine, Formation $formation = null, Request $request) : response
     {
 
         if(!$formation){
             $formation = new formation();
         }
 
         // Creation du formulaire et objet qu'on lui fait passer
         $form = $this->createForm(FormationType::class, $formation);
 
         //Récuperation des données du formulaire
         $form->handleRequest($request);
 
         // Vérification si le form est soumis et si les données sont saines
         if($form->isSubmitted() && $form->isValid())
         {   
             // On hydrate l'objet formation qu'on a crée avec les données du formulaire
             $formation = $form->getData();
             // On récupère l'entity Manager pour avoir acces a persist et flush
             $entityManager = $doctrine->getManager();
             // On prépare l'objet
             $entityManager->persist($formation);
             // On l'execute
             $entityManager->flush();
 
             return $this->redirectToRoute('app_formation');
         }
 
         // Vue qui vas afficher le formulaire
         return $this->render('formation/add.html.twig', [
             //generer le formulaire visuellement quand on utilise formAddformation
             'formAddFormation' => $form->createView()
             
         ]);
     }

     // Supprimer une formation
    #[Route('admin/formation/{id}/delete', name: 'delete_formation')]
    public function delete(ManagerRegistry $doctrine, Formation $formation): Response
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($formation);
        foreach ($formation->getSessions() as $session) {
            $formation->removeSession($session);
            $entityManager->remove($session); 
        }
        $entityManager->flush();

        return $this->redirectToRoute('app_formation');
    }
}
