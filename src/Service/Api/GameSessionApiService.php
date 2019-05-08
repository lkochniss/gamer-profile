<?php

namespace App\Service\Api;

use App\Entity\GameSession;
use App\Entity\User;
use App\Repository\GameRepository;
use App\Repository\GameSessionRepository;
use App\Service\Util\TimeConverterUtil;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class GameSessionApiService
 */
class GameSessionApiService
{
    /**
     * @var GameSessionRepository
     */
    private $gameSessionRepository;

    /**
     * @var GameRepository
     */
    private $gameRepository;

    /**
     * @var TimeConverterUtil
     */
    private $timeConverterService;

    /**
     * GameSessionApiService constructor.
     * @param GameSessionRepository $gameSessionRepository
     * @param GameRepository $gameRepository
     * @param TimeConverterUtil $timeConverterService
     */
    public function __construct(
        GameSessionRepository $gameSessionRepository,
        GameRepository $gameRepository,
        TimeConverterUtil $timeConverterService
    ) {
        $this->gameSessionRepository = $gameSessionRepository;
        $this->gameRepository = $gameRepository;
        $this->timeConverterService = $timeConverterService;
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function getSessionsLastDays(User $user): JsonResponse
    {
        $sessions = $this->gameSessionRepository->findForLastDays($user);

        return new JsonResponse($this->mapSessionData($sessions));
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function getSessionsThisYear(User $user): JsonResponse
    {
        $sessions = $this->gameSessionRepository->findForThisYear($user);

        return new JsonResponse($this->mapSessionData($sessions));
    }

    /**
     * @param int $year
     * @param User $user
     * @return JsonResponse
     */
    public function getSessionsForYear(int $year, User $user): JsonResponse
    {
        $sessions = $this->gameSessionRepository->findForYear($year, $user);

        return new JsonResponse($this->mapSessionData($sessions));
    }

    public function getSessionsForGame(int $gameId, User $user): JsonResponse {
        $game = $this->gameRepository->find($gameId);
        $data = [];

        if (!$game) {
            return new JsonResponse($data);
        }

        $sessions = $this->gameSessionRepository->findBy(['game' => $game, 'steamUserId' => $user->getSteamId()]);

        /**
         * @var GameSession $session
         */
        foreach ($sessions as $session) {
            $data[] = [
                'date' => $session->getCreatedAt()->format('d M Y'),
                'timeInMinutes' => $session->getDuration(),
                'timeForTooltip' => $this->timeConverterService->convertRecentTime($session->getDuration())
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * @param array $sessions
     * @return array
     */
    private function mapSessionData(array $sessions): array
    {
        $data = [];

        /**
         * @var GameSession $session
         */
        foreach ($sessions as $session) {
            $key = $session->getCreatedAt()->format('d M Y');
            if (!array_key_exists($key, $data)) {
                $data[$key] = 0;
            }
            $data[$key] += $session->getDuration();
        }

        $data = array_map(function ($month, $duration) {
            return [
                'date' => $month,
                'timeInMinutes' => $duration,
                'timeForTooltip' => $this->timeConverterService->convertRecentTime($duration)
            ];
        }, array_keys($data), $data);

        return $data;
    }
}
