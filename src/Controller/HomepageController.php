<?php

namespace App\Controller;

use App\Entity\OverallGameStats;
use App\Repository\GameRepository;

use App\Service\OverallGameStatsService;
use App\Service\Steam\Transformation\RecentlyPlayedGamesService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     */
    public function recentlyPlayed(
        GameRepository $gameRepository,
        RecentlyPlayedGamesService $recentlyPlayedService
    ): Response {
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

    /**
     * @param GameRepository $gameRepository
     * @return Response
     */
    public function newGames(GameRepository $gameRepository): Response
    {
        return $this->render('Homepage/newGames.html.twig', [
            'games' => $gameRepository->getNewGames()
        ]);
    }

    /**
     * @param OverallGameStatsService $overallGameStatsService
     * @return Response
     */
    public function mostPlayedGamePerMonth(OverallGameStatsService $overallGameStatsService): Response
    {
        return $this->render('Homepage/gameOfTheMonth.html.twig', [
            'bestGamePerMonth' => $overallGameStatsService->getMostPlayedGamePerMonth()
        ]);
    }

    /**
     * @param OverallGameStatsService $overallGameStatsService
     * @return Response
     */
    public function backendDashboard(OverallGameStatsService $overallGameStatsService): Response
    {
        return $this->render('Homepage/backendDashboard.html.twig', [
            'gameStats' => $overallGameStatsService->getAggregatedStats()
        ]);
    }
}
