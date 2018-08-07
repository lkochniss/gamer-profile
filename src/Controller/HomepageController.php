<?php

namespace App\Controller;

use App\Entity\GameSession;
use App\Repository\GameSessionRepository;
use App\Repository\OverallGameStatsRepository;
use App\Service\Stats\GameSessionService;
use App\Service\Stats\InvestedMoneyService;
use App\Service\Stats\WastedMoneyService;
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
     * @param WastedMoneyService $moneyService
     * @param UserInterface $user
     * @return RedirectResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateWasted(WastedMoneyService $moneyService, UserInterface $user): RedirectResponse
    {
        $moneyService->recalculate($user);

        return $this->redirectToRoute('homepage_dashboard');
    }

    /**
     * @param InvestedMoneyService $moneyService
     * @param UserInterface $user
     * @return RedirectResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateInvested(InvestedMoneyService $moneyService, UserInterface $user): RedirectResponse
    {
        $moneyService->recalculate($user);

        return $this->redirectToRoute('homepage_dashboard');
    }

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
     * @param GameSessionRepository $gameSessionRepository
     * @param UserInterface $user
     * @return Response
     */
    public function dashboard(
        OverallGameStatsRepository $overallGameStatsRepository,
        GameSessionRepository $gameSessionRepository,
        UserInterface $user
    ): Response {

        $gameSessions = $gameSessionRepository->findForThisMonth($user);
        $playedThisMonth = [];

        /**
         * @var GameSession $gameSession
         */
        foreach ($gameSessions as $gameSession) {
            $key = $gameSession->getGame()->getId();
            if (array_key_exists($key, $playedThisMonth) === false) {
                $playedThisMonth[$key] = [
                    'name' => $gameSession->getGame()->getName(),
                    'duration' => 0
                ];
            }

            $playedThisMonth[$key]['duration'] += $gameSession->getDuration();
        }

        usort($playedThisMonth, function (array $gameA, array $gameB) {
            return $gameA['duration'] > $gameB['duration'] ? -1: 1;
        });

        return $this->render('Homepage/dashboard.html.twig', [
            'gameStats' => $overallGameStatsRepository->findOneBy(['user' => $user]),
            'playedThisMonth' => array_slice($playedThisMonth, 0, 10)
        ]);
    }
}
