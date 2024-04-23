<?php

namespace App\Controller\api;

use App\Repository\EvenementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ApiEventsController extends AbstractController
{
    #[Route('/api/events', name: 'app_api_events', methods: ['GET'])]
    public function index(EvenementRepository $eventsRepo): JsonResponse
    {

        $events = $eventsRepo->findAll();

        $eventsArray = [];

        foreach ($events as $event) {
            # code...
            $eventDetail = [
                "name" => $event->getNom(),
                "lat" => $event->getLatitude(),
                "lon" => $event->getLongitude()
            ];
            array_push($eventsArray, $eventDetail);
        }

        // Retourner une réponse JSON avec les données récupérées ou d'autres données traitées
        return new JsonResponse(['success' => true, 'events' => $eventsArray]);
    }
}
