<?php

namespace App\Controller;

use App\Repository\GameRepository;

use App\Service\Steam\Transformation\RecentlyPlayedGamesService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HomepageController
 */
class HomepageController extends Controller
{

    /**
     * @param GameRepository $gameRepository
     * @param RecentlyPlayedGamesService $recentlyPlayedService
     * @return Response
     */
    public function recentlyPlayed(
        GameRepository $gameRepository,
        RecentlyPlayedGamesService $recentlyPlayedService
    ): Response
    {
        $recentlyPlayedGames = $gameRepository->getRecentlyPlayedGames();
        return $this->render('Homepage/recentlyPlayed.html.twig', [
            'games' => $recentlyPlayedService->sortRecentlyPlayedGamesByLastSession($recentlyPlayedGames)
        ]);
    }

    /**
     * @param GameRepository $gameRepository
     * @return Response
     */
    public function mostPlayed(GameRepository $gameRepository): Response
    {
        return $this->render('Homepage/mostPlayed.html.twig', [
                'games' => $gameRepository->getMostPlayedGames(10)
        ]);
    }

    public function newGames(GameRepository $gameRepository): Response
    {
        return $this->render('Homepage/newGames.html.twig', [
            'games' => $gameRepository->getNewGames()
        ]);
    }
}
