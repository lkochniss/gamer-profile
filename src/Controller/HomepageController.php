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
    public function mostPlayedGamePerMonth(
        ?int $year,
        GameSessionsPerMonthRepository $gameSessionsPerMonthRepository
    ): Response {
        $now = new \DateTime();
        if (is_null($year)) {
            $year = $now->format('Y');
        }

        $yearsWithSessions = $this->getYearsWithGameSessions($gameSessionsPerMonthRepository);

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
            'yearsWithSessions' => $yearsWithSessions,
            'currentYear' => $year
        ]);
    }

    public function mostPlayedGamesPerYear(GameSessionsPerMonthRepository $gameSessionsPerMonthRepository): Response
    {
        $now = new \DateTime();

        $oldestEntry = $gameSessionsPerMonthRepository->findOneBy([]);
        $gameSessionsPerYear = [];
        for ($i = $oldestEntry->getMonth()->format('Y'); $i <= $now->format('Y'); $i++) {
            $sessionsPerYear = $gameSessionsPerMonthRepository->findByYear($i);

            if (!array_key_exists($i, $gameSessionsPerYear)) {
                $gameSessionsPerYear[$i] = [];
            }

            /**
             * @var GameSessionsPerMonth $session
             */
            foreach ($sessionsPerYear as $session) {
                if (!array_key_exists($session->getGame()->getSteamAppId(), $gameSessionsPerYear[$i])) {
                    $gameSessionsPerYear[$i][$session->getGame()->getSteamAppId()] = [
                        'game' => $session->getGame(),
                        'duration' => 0
                    ];
                }

                $gameSessionsPerYear[$i][$session->getGame()->getSteamAppId()]['duration'] += $session->getDuration();
            }

            usort($gameSessionsPerYear[$i], function (array $sessionA, array $sessionB) {
                return $sessionA['duration'] > $sessionB['duration'] ? -1: 1;
            });

            array_splice($gameSessionsPerYear[$i], 3);
        }

        return $this->render('Homepage/game-of-the-year.html.twig', [
            'bestGamesPerYear' => $gameSessionsPerYear,
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
     * @param GameSessionsPerMonthRepository $gameSessionsPerMonthRepository
     * @param GameRepository $gameRepository
     * @return Response
     */
    public function backendDashboard(
        OverallGameStatsRepository $overallGameStatsRepository,
        GameSessionRepository $gameSessionRepository,
        GameSessionsPerMonthRepository $gameSessionsPerMonthRepository,
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

        $now = new \DateTime();
        $yearsWithSessions = $this->getYearsWithGameSessions($gameSessionsPerMonthRepository);

        return $this->render('Homepage/backend-dashboard.html.twig', [
            'gameStats' => $overallGameStatsRepository->findOneBy(['identifier' => getenv('STEAM_USER_ID')]),
            'gameStatus' => $gameStatus,
            'playedThisMonth' => array_slice($playedThisMonth, 0, 10),
            'yearsWithSessions' => $yearsWithSessions,
            'currentYear' => $now->format('Y')
        ]);
    }

    /**
     * @param $gameSessionsPerMonthRepository
     * @return array
     */
    private function getYearsWithGameSessions($gameSessionsPerMonthRepository): array
    {
        $now = new \DateTime();
        $oldestEntry = $gameSessionsPerMonthRepository->findOneBy([]);
        $yearsWithSessions = [];
        for ($i = $oldestEntry->getMonth()->format('Y'); $i <= $now->format('Y'); $i++) {
            if ($gameSessionsPerMonthRepository->findByYear($i)) {
                $yearsWithSessions[] = $i;
            }
        }

        return $yearsWithSessions;
    }
}
