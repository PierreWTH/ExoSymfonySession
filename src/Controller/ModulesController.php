<?php

namespace App\Controller;

use App\Entity\Modules;
use App\Form\ModuleType;
use App\Entity\Categorie;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModulesController extends AbstractController
{
    #[Route('/modules', name: 'app_modules')]
    public function index(ManagerRegistry $doctrine): Response
    {   
        $categories = $doctrine->getRepository(Categorie::Class)->findBy([], ["nomCategorie"=>"ASC"]);
        $modules = $doctrine->getRepository(Modules::Class)->findBy([], ["nomModule"=>"ASC"]);
        return $this->render('modules/index.html.twig', [
            'categories' => $categories,
            'modules' => $modules
        ]);
    }

     // Ajouter ou editer une catégorie
     #[Route('admin/module/add', name: 'add_new_module')]
     #[Route('admin/module/{id}/edit', name: 'edit_module')]
    public function add(ManagerRegistry $doctrine, Modules $module = null, Request $request) : response
    {

        if(!$module){
            $module = new Modules();
        }

        // Creation du formulaire et objet qu'on lui fait passer
        $form = $this->createForm(ModuleType::class, $module);

        //Récuperation des données du formulaire
        $form->handleRequest($request);

        // Vérification si le form est soumis et si les données sont saines
        if($form->isSubmitted() && $form->isValid())
        {   
            // On hydrate l'objet module qu'on a crée avec les données du formulaire
            $module = $form->getData();
            // On récupère l'entity Manager pour avoir acces a persist et flush
            $entityManager = $doctrine->getManager();
            // On prépare l'objet
            $entityManager->persist($module);
            // On l'execute
            $entityManager->flush();

            return $this->redirectToRoute('app_modules');
        }
        
        // Vue qui vas afficher le formulaire
        return $this->render('modules/add.html.twig', [
            //generer le formulaire visuellement quand on utilise formAddmodule
            'formAddModule' => $form->createView()
            
        ]);
    }

    // Supprimer un module
    #[Route('admin/module/{id}/delete', name: 'delete_module')]
    public function delete(ManagerRegistry $doctrine, Modules $module = null): Response
    {
        if ($module)
        {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($module);
            foreach ($module->getProgrammes() as $programme) 
            {
                $module->removeProgramme($programme);
                $entityManager->remove($programme); 
            }
            $entityManager->flush();

        }
        
        return $this->redirectToRoute('app_modules');
    }
    
}
