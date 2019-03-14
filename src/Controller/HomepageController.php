<?php

namespace App\Controller;

use App\Entity\GameSessionsPerMonth;
use App\Entity\User;
use App\Repository\GameSessionsPerMonthRepository;
use App\Repository\OverallGameStatsRepository;
use App\Service\Stats\GameSessionService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class HomepageController
 */
class HomepageController extends Controller
{
    /**
     * @param GameSessionService $gameSessionService
     * @param UserInterface $user
     * @return RedirectResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateSessions(GameSessionService $gameSessionService, UserInterface $user): RedirectResponse
    {
        $gameSessionService->recalculate($user);

        return $this->redirectToRoute('homepage_dashboard');
    }

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
        $gameSessions = $gameSessionsPerMonthRepository->findByMonth($month, $user);

        usort($gameSessions, function (GameSessionsPerMonth $sessionA, GameSessionsPerMonth $sessionB) {
            return $sessionA->getDuration() > $sessionB->getDuration() ? -1: 1;
        });

        $now = new \DateTime();
        $yearsWithSessions = $this->getYearsWithGameSessions($gameSessionsPerMonthRepository, $user);

        return $this->render('Homepage/dashboard.html.twig', [
            'gameStats' => $overallGameStatsRepository->findOneBy(['user' => $user]),
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
    private function getYearsWithGameSessions(GameSessionsPerMonthRepository $gameSessionsPerMonthRepository, User $user): array
    {
        $now = new \DateTime();
        $oldestEntry = $gameSessionsPerMonthRepository->findOneBy([]);
        $yearsWithSessions = [];

        if (is_null($oldestEntry)) {
            return $yearsWithSessions;
        }

        for ($i = $oldestEntry->getMonth()->format('Y'); $i <= $now->format('Y'); $i++) {
            if ($gameSessionsPerMonthRepository->findByYear($i, $user)) {
                $yearsWithSessions[] = $i;
            }
        }

        return $yearsWithSessions;
    }
}
