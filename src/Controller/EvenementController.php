<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Media;
use App\Form\EvenementType;
use App\Form\MediaType;
use App\Repository\EvenementRepository;
use App\Repository\LieuRepository;
use App\Repository\MediaRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EvenementController extends AbstractController
{
    // #[Route('/evenement', name: 'app_evenement')]
    // public function index(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $evenement = new Evenement();
    //     $form = $this->createForm(EvenementType::class, $evenement);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager = $entityManager()->getManager();
    //         $entityManager->persist($evenement);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_evenement');
    //     }
    //     $events = $entityManager->getRepository(Evenement::class)->findAll();

    //     return $this->render('evenement/index.html.twig', [
    //         'evenementForm' => $form->createView(),
    //         'events' => $events
    //     ]);
    // }
    #[Route('/events', name: 'app_events')]
    public function events(EvenementRepository $repository): Response
    {
        $events = $repository->findAll();
        // Rendu de la vue avec les catégories et le formulaire.
        return $this->render('evenement/eventView.html.twig', [
            'events' => $events
        ]);
    }

    #[Route('/evenement', name: 'app_evenement')]
    #[Route('/evenement/{id}', name: 'app_event_update')]
    public function evenement(EvenementRepository $repository, Request $request, EntityManagerInterface $manager, $id = null): Response
    {
        // Récupérer toutes les catégories depuis la base de données.
        $events = $repository->findAll();

        // Vérifier si un identifiant est fourni, cela signifie qu'on veut modifier une catégorie existante.
        // Sinon, on crée une nouvelle instance de Category.
        if ($id) {
            $event = $repository->find($id);
        } else {
            $event = new Evenement();
        }

        // Création du formulaire à partir de la classe CategoryType.
        $form = $this->createForm(EvenementType::class, $event);

        // Analyse de la requête HTTP.
        $form->handleRequest($request);

        // Vérification si le formulaire a été soumis et est valide.
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération des données du formulaire.
            $event = $form->getData();

            // Persistation des données en base de données.
            $manager->persist($event);

            // Exécution de la transaction.
            $manager->flush();

            // Ajout d'un message flash pour indiquer que la catégorie a été ajoutée avec succès.
            $this->addFlash('success', 'L\'evenement a bien été ajouté');

            // Redirection vers la route admin_category.
            return $this->redirectToRoute('app_events');
        }

        // Rendu de la vue avec les catégories et le formulaire.
        return $this->render('evenement/index.html.twig', [
            'events' => $events,
            'form' => $form->createView()
        ]);
    }

    // Action pour supprimer une catégorie.
    #[Route('/evenement/delete/{id}', name: 'app_event_delete')]
    public function delete(EvenementRepository $repository, EntityManagerInterface $manager, $id = null): Response
    {
        // Vérifier si un identifiant est fourni pour la catégorie à supprimer.

            // Récupération de la catégorie à supprimer.
            $event = $repository->find($id);


        // Suppression de la catégorie.
        $manager->remove($event);
        $manager->flush();

        // Redirection vers la page d'administration des catégories.
        return $this->redirectToRoute('app_evenement');
    }

}
