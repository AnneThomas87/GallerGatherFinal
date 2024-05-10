<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Récupère l'erreur de connexion s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();
        // Dernier nom d'utilisateur saisi par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();
    
        // Rend la vue de connexion avec les données récupérées
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername, // Dernier nom d'utilisateur saisi
            'error' => $error, // Erreur de connexion
        ]);
    }
    
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Cette méthode peut être vide car elle sera interceptée par la configuration de sécurité
        // lorsqu'un utilisateur se déconnecte
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}    