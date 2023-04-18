<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategorieController extends AbstractController
{
    #[Route('/categorie', name: 'app_categorie')]
    public function index(ManagerRegistry $doctrine): Response
    {   
        $categories = $doctrine->getRepository(Categorie::Class)->findBy([], ["nomCategorie"=>"ASC"]);
        return $this->render('categorie/index.html.twig', [
            'categories' => $categories,
        ]);
    }

     // Ajouter ou editer une catégorie
     #[Route('admin/categorie/add', name: 'add_categorie')]
     #[Route('admin/categorie/{id}/edit', name: 'edit_categorie')]
    public function add(ManagerRegistry $doctrine, Categorie $categorie = null, Request $request) : response
    {

        if(!$categorie){
            $categorie = new Categorie();
        }

        // Creation du formulaire et objet qu'on lui fait passer
        $form = $this->createForm(CategorieType::class, $categorie);

        //Récuperation des données du formulaire
        $form->handleRequest($request);

        // Vérification si le form est soumis et si les données sont saines
        if($form->isSubmitted() && $form->isValid())
        {   
            // On hydrate l'objet Categorie qu'on a crée avec les données du formulaire
            $categorie = $form->getData();
            // On récupère l'entity Manager pour avoir acces a persist et flush
            $entityManager = $doctrine->getManager();
            // On prépare l'objet
            $entityManager->persist($categorie);
            // On l'execute
            $entityManager->flush();

            return $this->redirectToRoute('app_categorie');
        }

        // Vue qui vas afficher le formulaire
        return $this->render('categorie/add.html.twig', [
            //generer le formulaire visuellement quand on utilise formAddcategorie
            'formAddCategorie' => $form->createView()
            
        ]);
    }

    // Supprimer une categorie
    #[Route('admin/categorie/{id}/delete', name: 'delete_categorie')]
    public function delete(ManagerRegistry $doctrine, Categorie $categorie): Response
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($categorie);
        foreach ($categorie->getModules() as $module) {
            $categorie->removeModule($module);
            $entityManager->remove($module); 

            foreach ($module->getProgrammes() as $programme) {
                $module->removeProgramme($programme);
                $entityManager->remove($programme); 
            }
        }

        $entityManager->flush();

    return $this->redirectToRoute('app_categorie');
    }

}
