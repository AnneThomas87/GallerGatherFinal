<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategorieController extends AbstractController
{
    // Action pour afficher toutes les catégories et permettre leur modification ou ajout.
    #[Route('/categorie', name: 'admin_category')]
    #[Route('/categorie/{id}', name: 'admin_category_update')]
    public function index(CategorieRepository $repository, Request $request, EntityManagerInterface $manager, $id=null): Response
    {
        // Récupérer toutes les catégories depuis la base de données.
        $categories = $repository->findAll();

        // Vérifier si un identifiant est fourni, cela signifie qu'on veut modifier une catégorie existante.
        // Sinon, on crée une nouvelle instance de Category.
        if($id){
            $category = $repository->find($id);
        } else {
            $category = new Categorie();
        }
        
        // Création du formulaire à partir de la classe CategoryType.
        $form = $this->createForm(CategorieType::class, $category);

        // Analyse de la requête HTTP.
        $form->handleRequest($request);
 
        // Vérification si le formulaire a été soumis et est valide.
        if ($form->isSubmitted() && $form->isValid()){
            // Récupération des données du formulaire.
            $category = $form->getData();
 
            // Persistation des données en base de données.
            $manager->persist($category);
 
            // Exécution de la transaction.
            $manager->flush();

            // Ajout d'un message flash pour indiquer que la catégorie a été ajoutée avec succès.
            $this->addFlash('success', 'La catégorie a bien été ajoutée');
 
            // Redirection vers la route admin_category.
            return $this->redirectToRoute('admin_category');
        }
           
        // Rendu de la vue avec les catégories et le formulaire.
        return $this->render('categorie/index.html.twig', [
            'categories' => $categories,
            'form' => $form->createView()
        ]);
    }

    // Action pour supprimer une catégorie.
    #[Route('/categorie/delete/{id}', name: 'admin_category_delete')]
    public function delete(CategorieRepository $repository, EntityManagerInterface $manager, $id = null):Response
    {   
        // Vérifier si un identifiant est fourni pour la catégorie à supprimer.
        if($id){
            // Récupération de la catégorie à supprimer.
            $category = $repository->find($id);
        }
        
        // Suppression de la catégorie.
        $manager->remove($category);
        $manager->flush();
        
        // Redirection vers la page d'administration des catégories.
        return $this->redirectToRoute('admin_category');
    }
}
