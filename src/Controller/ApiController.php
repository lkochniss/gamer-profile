<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\GameSession;
use App\Repository\GameRepository;
use App\Repository\GameSessionRepository;
use App\Repository\PlaytimePerMonthRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ApiController
 */
class ApiController extends Controller
{
    /**
     * @param PlaytimePerMonthRepository $playtimePerMonthRepository
     * @return JsonResponse
     */
    public function sessionsPerMonth(PlaytimePerMonthRepository $playtimePerMonthRepository): JsonResponse
    {
        $playtimePerMonth = $playtimePerMonthRepository->findAll();

        $data = [];
        foreach ($playtimePerMonth as $playtime) {
            $data[] = [
                'total' => $playtime->getDuration(),
                'month' => $playtime->getMonth()->format('m-y')
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * @param GameSessionRepository $gameSessionRepository
     * @return JsonResponse
     */
    public function sessionsLastDays(GameSessionRepository $gameSessionRepository): JsonResponse
    {
        $sessions = $gameSessionRepository->findForLastDays();

        $data = [];

        /**
         * @var GameSession $session
         */
        foreach ($sessions as $session) {
            if (!array_key_exists($session->getCreatedAt()->format('d-m-y'), $data)) {
                $data[$session->getCreatedAt()->format('d-m-y')] = 0;
            }
            $data[$session->getCreatedAt()->format('d-m-y')] += $session->getDuration();
        }

        $data = array_map(function ($month, $duration) {
            return [
                'total' => $duration,
                'month' => $month
            ];
        }, array_keys($data), $data);

        return new JsonResponse($data);
    }

    /**
     * @param int $id
     * @param GameRepository $gameRepository
     * @return JsonResponse
     */
    public function sessionsForGame(int $id, GameRepository $gameRepository): JsonResponse
    {
        /**
         * @var Game $game
         */
        $game = $gameRepository->find($id);

        if (!$game) {
            throw new NotFoundHttpException();
        }

        $data = [];

        /**
         * @var GameSession $session
         */
        foreach ($game->getGameSessions() as $session) {
            $data[] = [
                'total' => $session->getDuration(),
                'month' => $session->getCreatedAt()->format('d-m-y')
            ];
        }

        return new JsonResponse($data);
    }
}
