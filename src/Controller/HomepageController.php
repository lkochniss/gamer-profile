<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\GameSession;
use App\Entity\GameSessionsPerMonth;
use App\Repository\GameRepository;
use App\Repository\GameSessionRepository;
use App\Repository\GameSessionsPerMonthRepository;
use App\Repository\OverallGameStatsRepository;
use App\Service\Stats\GameSessionService;
use App\Service\Stats\InvestedMoneyService;
use App\Service\Stats\WastedMoneyService;
use App\Service\Transformation\RecentlyPlayedGamesService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
        return $this->render('Homepage/recently-played.html.twig', [
            'games' => $recentlyPlayedService->sortRecentlyPlayedGamesByLastSession($recentlyPlayedGames)
        ]);
    }

    /**
     * @param GameRepository $gameRepository
     * @return Response
     */
    public function mostPlayed(GameRepository $gameRepository): Response
    {
        return $this->render('Homepage/most-played.html.twig', [
            'games' => $gameRepository->getMostPlayedGames(12)
        ]);
    }

    /**
     * @param GameRepository $gameRepository
     * @return Response
     */
    public function newGames(GameRepository $gameRepository): Response
    {
        return $this->render('Homepage/new-games.html.twig', [
            'games' => $gameRepository->getNewGames()
        ]);
    }

    /**
     * @param int|null $year
     * @param GameSessionsPerMonthRepository $gameSessionsPerMonthRepository
     * @return Response
     */
    public function mostPlayedGamePerMonth(?int $year, GameSessionsPerMonthRepository $gameSessionsPerMonthRepository): Response
    {
        $now = new \DateTime();
        if (is_null($year)){
            $year = $now->format('Y');
        }

        $oldestEntry = $gameSessionsPerMonthRepository->findOneBy([]);
        $yearsWithSessions = [];
        for ($potentialYear = $oldestEntry->getMonth()->format('Y'); $potentialYear <= $now->format('Y'); $potentialYear++) {
            if ($gamesPerMonth = $gameSessionsPerMonthRepository->findByYear($potentialYear)){
                $yearsWithSessions[] = $potentialYear;
            }
        }

        $gamesPerMonth = $gameSessionsPerMonthRepository->findByYear($year);
        $bestGamePerMonth = [];
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

        return $this->render('Homepage/game-of-the-month.html.twig', [
            'bestGamePerMonth' => $bestGamePerMonth,
            'yearsWithSessions' => $yearsWithSessions
        ]);
    }

    /**
     * @param WastedMoneyService $moneyService
     * @return RedirectResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateWasted(WastedMoneyService $moneyService): RedirectResponse
    {
        $moneyService->recalculate();

        return $this->redirectToRoute('homepage_backend_dashboard');
    }

    /**
     * @param InvestedMoneyService $moneyService
     * @return RedirectResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateInvested(InvestedMoneyService $moneyService): RedirectResponse
    {
        $moneyService->recalculate();

        return $this->redirectToRoute('homepage_backend_dashboard');
    }

    /**
     * @param GameSessionService $gameSessionService
     * @return RedirectResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateSessions(GameSessionService $gameSessionService): RedirectResponse
    {
        $gameSessionService->recalculate();

        return $this->redirectToRoute('homepage_backend_dashboard');
    }

    /**
     * @param OverallGameStatsRepository $overallGameStatsRepository
     * @param GameSessionRepository $gameSessionRepository
     * @param GameRepository $gameRepository
     * @return Response
     */
    public function backendDashboard(
        OverallGameStatsRepository $overallGameStatsRepository,
        GameSessionRepository $gameSessionRepository,
        GameRepository $gameRepository
    ): Response {

        $gameSessions = $gameSessionRepository->findForThisMonth();
        $playedThisMonth = [];

        /**
         * @var GameSession $gameSession
         */
        foreach ($gameSessions as $gameSession) {
            $key = $gameSession->getGame()->getId();
            if (array_key_exists($key, $playedThisMonth) === false) {
                $playedThisMonth[$key] = [
                    'id' => $gameSession->getGame()->getId(),
                    'name' => $gameSession->getGame()->getName(),
                    'duration' => 0
                ];
            }

            $playedThisMonth[$key]['duration'] += $gameSession->getDuration();
        }

        usort($playedThisMonth, function (array $gameA, array $gameB) {
            return $gameA['duration'] > $gameB['duration'] ? -1: 1;
        });

        $games = $gameRepository->findAll();
        $gameStatus = [
            'all' => count($games),
            Game::OPEN => 0,
            Game::PAUSED => 0,
            Game::PLAYING => 0,
            Game::FINISHED => 0,
            Game::GIVEN_UP => 0
        ];

        foreach ($games as $game) {
            $gameStatus[$game->getStatus()] += 1;
        }

        return $this->render('Homepage/backend-dashboard.html.twig', [
            'gameStats' => $overallGameStatsRepository->findOneBy(['identifier' => getenv('STEAM_USER_ID')]),
            'gameStatus' => $gameStatus,
            'playedThisMonth' => array_slice($playedThisMonth, 0, 10)
        ]);
    }
}
