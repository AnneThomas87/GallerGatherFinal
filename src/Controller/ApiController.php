<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ApiController extends AbstractController
{
    #[Route('/api/match', name: 'app_api_match', methods: ['POST', 'GET'])]
    public function index(Request $request): Response
    {
        // Récupérer les données JSON envoyées depuis la requête
        $jsonData = json_decode($request->getContent(), true);

        // Récupérer la valeur de l'attribut 'id' dans les données JSON
        $id = $jsonData['id'];

        // Faites ce que vous voulez avec l'ID récupéré
        // Par exemple, vous pouvez utiliser l'ID pour effectuer une recherche ou une opération en base de données

        // Retourner une réponse JSON avec les données récupérées ou d'autres données traitées
        return new JsonResponse(['success' => true, 'id' => $id]);
    }
}
