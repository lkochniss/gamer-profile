<?php

namespace App\Controller;

use App\Repository\GameRepository;
use App\Service\Steam\RecentlyPlayedService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HomepageController
 */
class HomepageController extends Controller
{

    /**
     * @param GameRepository $gameRepository
     * @param RecentlyPlayedService $recentlyPlayedService
     * @return Response
     */
    public function recentlyPlayed(GameRepository $gameRepository, RecentlyPlayedService $recentlyPlayedService)
    {
        $recentlyPlayedGames = $gameRepository->getRecentlyPlayedGames();
        return $this->render('Homepage/recentlyPlayed.html.twig', [
            'games' => $recentlyPlayedService->sortRecentlyPlayedByLastSession($recentlyPlayedGames)
        ]);
    }

    /**
     * @param GameRepository $gameRepository
     * @return Response
     */
    public function mostPlayed(GameRepository $gameRepository)
    {
        return $this->render('Homepage/mostPlayed.html.twig', [
                'games' => $gameRepository->getMostPlayedGames(10)
            ]
        );
    }
}
