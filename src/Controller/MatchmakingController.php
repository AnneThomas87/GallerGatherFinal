<?php

namespace App\Controller;

use App\Repository\LieuRepository;
use App\Repository\MediaRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class MatchmakingController extends AbstractController
{
    #[IsGranted('ROLE_PRO')]
    #[Route('/matchmaking/pro', name: 'app_matchmakingPro')]
    public function matchmakingPro(MediaRepository $mediaRepos): Response
    {
       $media = $mediaRepos->findImageOrga();

        return $this->render('matchmaking/pro.html.twig', [
         'medias' => $media
        ]);
    }

    #[IsGranted('ROLE_ORGA')]
    #[Route('/matchmaking/orga', name: 'app_matchmakingOrga')]
    public function matchmakingOrga(MediaRepository $mediaRepos): Response
    {


       $media = $mediaRepos->findImagePro();

        return $this->render('matchmaking/orga.html.twig', [
         'medias' => $media
        ]);
    }
}
