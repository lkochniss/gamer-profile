<?php

namespace App\Controller;

use App\Entity\GameSessionsPerMonth;
use App\Entity\User;
use App\Repository\GameRepository;
use App\Repository\GameSessionsPerMonthRepository;
use App\Repository\GameStatsRepository;
use App\Repository\OverallGameStatsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class HomepageController
 */
class HomepageController extends AbstractController
{
    /**
     * @param OverallGameStatsRepository $overallGameStatsRepository
     * @param GameSessionsPerMonthRepository $gameSessionsPerMonthRepository
     * @param UserInterface $user
     * @return Response
     */
    public function dashboard(
        OverallGameStatsRepository $overallGameStatsRepository,
        GameSessionsPerMonthRepository $gameSessionsPerMonthRepository,
        UserInterface $user
    ): Response {

        $month = new \DateTime('first day of this month 00:00:00');
        $gameSessions = $gameSessionsPerMonthRepository->findByMonth($month, $user->getSteamId());

        usort($gameSessions, function (GameSessionsPerMonth $sessionA, GameSessionsPerMonth $sessionB) {
            return $sessionA->getDuration() > $sessionB->getDuration() ? -1: 1;
        });

        $now = new \DateTime();
        $yearsWithSessions = $this->getYearsWithGameSessions($gameSessionsPerMonthRepository, $user);

        return $this->render('Homepage/dashboard.html.twig', [
            'gameStats' => $overallGameStatsRepository->findOneBy(['steamUserId' => $user->getSteamId()]),
            'playedThisMonth' => array_slice($gameSessions, 0, 10),
            'yearsWithSessions' => $yearsWithSessions,
            'currentYear' => $now->format('Y')
        ]);
    }

    public function gameOfTheMonth(
        ?int $year,
        GameSessionsPerMonthRepository $gameSessionsPerMonthRepository,
        UserInterface $user
    ) {
        $now = new \DateTime();
        if (is_null($year)) {
            $year = $now->format('Y');
        }

        $yearsWithSessions = $this->getYearsWithGameSessions($gameSessionsPerMonthRepository, $user);

        $gamesPerMonth = $gameSessionsPerMonthRepository->findByYear($year, $user->getSteamId());
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

    public function gameOfTheYear(GameSessionsPerMonthRepository $gameSessionsPerMonthRepository, UserInterface $user)
    {
        $now = new \DateTime();
        $oldestEntry = $gameSessionsPerMonthRepository->findOneBy([]);
        $gameSessionsPerYear = [];

        for ($i = $oldestEntry->getMonth()->format('Y'); $i <= $now->format('Y'); $i++) {
            $sessionsPerYear = $gameSessionsPerMonthRepository->findByYear($i, $user->getSteamId());

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

    public function mostPlayedGames(GameStatsRepository $gameStatsRepository, UserInterface $user): Response
    {
        return $this->render('Homepage/most-played-games.html.twig', [
            'gamesStats' => $gameStatsRepository->getMostPlayedGames(12, $user->getSteamId())
        ]);
    }

    /**
     * @param GameSessionsPerMonthRepository $gameSessionsPerMonthRepository
     * @param User $user
     * @return array
     */
    private function getYearsWithGameSessions(
        GameSessionsPerMonthRepository $gameSessionsPerMonthRepository,
        User $user
    ): array {
        $now = new \DateTime();
        $oldestEntry = $gameSessionsPerMonthRepository->findOneBy([]);
        $yearsWithSessions = [];

        if (is_null($oldestEntry)) {
            return $yearsWithSessions;
        }

        for ($i = $oldestEntry->getMonth()->format('Y'); $i <= $now->format('Y'); $i++) {
            if ($gameSessionsPerMonthRepository->findByYear($i, $user->getSteamId())) {
                $yearsWithSessions[] = $i;
            }
        }

        return $yearsWithSessions;
    }
}
