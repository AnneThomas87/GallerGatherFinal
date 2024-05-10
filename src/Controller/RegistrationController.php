<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager): Response
    {
        // Crée une nouvelle instance de l'entité User
        $user = new User();
        // Crée un formulaire d'inscription en utilisant RegistrationFormType et l'entité User
        $form = $this->createForm(RegistrationFormType::class, $user);
        // Traite la requête HTTP pour remplir le formulaire avec les données envoyées
        $form->handleRequest($request);
    
        // Vérifie si le formulaire a été soumis et s'il est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Définit les rôles de l'utilisateur en fonction de ce qui a été choisi dans le formulaire
            $user->setRoles([$form->get('role')->getData()]);
            // Hache le mot de passe de l'utilisateur
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
    
            // Persiste l'utilisateur dans la base de données
            $entityManager->persist($user);
            // Exécute les requêtes SQL persistées
            $entityManager->flush();
    
            // Connecte automatiquement l'utilisateur après l'inscription
            return $security->login($user, LoginFormAuthenticator::class, 'main');
        }
    
        // Rend la vue de l'inscription avec le formulaire
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(), // Crée la vue du formulaire pour le rendu dans le template Twig
        ]);
    }
    
}
