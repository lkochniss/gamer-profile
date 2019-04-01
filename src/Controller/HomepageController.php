<?php

namespace App\Controller;

use App\Entity\GameSessionsPerMonth;
use App\Entity\User;
use App\Repository\GameSessionsPerMonthRepository;
use App\Repository\OverallGameStatsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class HomepageController
 */
class HomepageController extends Controller
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
