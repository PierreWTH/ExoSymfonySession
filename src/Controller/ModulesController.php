<?php

namespace App\Controller;

use App\Entity\Modules;
use App\Form\ModuleType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModulesController extends AbstractController
{
    #[Route('/module', name: 'app_modules')]
    public function index(): Response
    {
        return $this->render('modules/index.html.twig', [
            'controller_name' => 'ModulesController',
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

            return $this->redirectToRoute('app_module');
        }

        // Vue qui vas afficher le formulaire
        return $this->render('modules/add.html.twig', [
            //generer le formulaire visuellement quand on utilise formAddmodule
            'formAddModule' => $form->createView()
            
        ]);
    }
    
}
