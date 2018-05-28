<?php

namespace App\Controller;

use App\Entity\GameSessionsPerMonth;
use App\Repository\GameRepository;
use App\Repository\GameSessionsPerMonthRepository;
use App\Repository\OverallGameStatsRepository;
use App\Repository\PlaytimePerMonthRepository;
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
            'games' => $gameRepository->getMostPlayedGames(12)
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
     * @param GameSessionsPerMonthRepository $gameSessionsPerMonthRepository
     * @return Response
     */
    public function mostPlayedGamePerMonth(GameSessionsPerMonthRepository $gameSessionsPerMonthRepository): Response
    {
        $bestGamePerMonth = [];
        $gamesPerMonth = $gameSessionsPerMonthRepository->findAll();

        /**
         * @var GameSessionsPerMonth $gamePerMonth
         */
        foreach ($gamesPerMonth as $gamePerMonth) {
            $yearMonthKey = $gamePerMonth->getMonth()->format('Y-m');
            if (!array_key_exists($yearMonthKey, $bestGamePerMonth)) {
                $bestGamePerMonth[$yearMonthKey] = $gamePerMonth;
            }

            if ($bestGamePerMonth[$yearMonthKey]->getDuration() < $gamePerMonth->getDuration()) {
                $bestGamePerMonth[$yearMonthKey] = $gamePerMonth;
            }
        }

        return $this->render('Homepage/gameOfTheMonth.html.twig', [
            'bestGamePerMonth' => $bestGamePerMonth
        ]);
    }

    /**
     * @param OverallGameStatsRepository $overallGameStatsRepository
     * @param PlaytimePerMonthRepository $playtimePerMonthRepository
     * @return Response
     */
    public function backendDashboard(
        OverallGameStatsRepository $overallGameStatsRepository,
        PlaytimePerMonthRepository $playtimePerMonthRepository
    ): Response {
        return $this->render('Homepage/backendDashboard.html.twig', [
            'gameStats' => $overallGameStatsRepository->findOneBy(['identifier' => getenv('STEAM_USER_ID')]),
            'playtimePerMonths' => $playtimePerMonthRepository->findAll()
        ]);
    }
}
