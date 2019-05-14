<?php

namespace App\Service\Api;

use App\Entity\GameSessionsPerMonth;
use App\Entity\User;
use App\Repository\GameRepository;
use App\Repository\GameSessionsPerMonthRepository;
use App\Service\Util\TimeConverterUtil;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class GameSessionApiService
 */
class GameSessionsPerMonthApiService
{
    /**
     * @var GameSessionsPerMonthRepository
     */
    private $gameSessionsPerMonthRepository;

    /**
     * @var GameRepository
     */
    private $gameRepository;

    /**
     * @var TimeConverterUtil
     */
    private $timeConverterService;

    /**
     * GameSessionForGameApiService constructor.
     * @param GameSessionsPerMonthRepository $gameSessionsPerMonthRepository
     * @param GameRepository $gameRepository
     * @param TimeConverterUtil $timeConverterService
     */
    public function __construct(
        GameSessionsPerMonthRepository $gameSessionsPerMonthRepository,
        GameRepository $gameRepository,
        TimeConverterUtil $timeConverterService
    ) {
        $this->gameSessionsPerMonthRepository = $gameSessionsPerMonthRepository;
        $this->gameRepository = $gameRepository;
        $this->timeConverterService = $timeConverterService;
    }

    /**
     * @param int $gameId
     * @param User $user
     * @return JsonResponse
     */
    public function getSessionsPerMonthForGame(int $gameId, User $user): JsonResponse
    {
        $game = $this->gameRepository->find($gameId);
        $data = [];

        if (is_null($game)) {
            return new JsonResponse($data);
        }

        $sessions = $this->gameSessionsPerMonthRepository->findBy([
            'game' => $game,
            'steamUserId' => $user->getSteamId()
        ]);

        /**
         * @var GameSessionsPerMonth $session
         */
        foreach ($sessions as $session) {
            $data[] = [
                'date' => $session->getMonth()->format('M Y'),
                'timeInMinutes' => $session->getDuration(),
                'timeForTooltip' => $this->timeConverterService->convertRecentTime($session->getDuration())
            ];
        }

        return new JsonResponse($data);
    }
}
