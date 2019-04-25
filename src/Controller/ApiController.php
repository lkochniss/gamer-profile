<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\GameSession;
use App\Repository\GameRepository;
use App\Repository\GameSessionRepository;
use App\Repository\GameSessionsPerMonthRepository;
use App\Service\Api\PlaytimePerMonthApiService;
use App\Service\Util\TimeConverterUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class ApiController
 */
class ApiController extends AbstractController
{
    /**
     * @var TimeConverterUtil
     */
    private $timeConverterService;

    /**
     * ApiController constructor.
     * @param TimeConverterUtil $timeConverterService
     */
    public function __construct(TimeConverterUtil $timeConverterService)
    {
        $this->timeConverterService = $timeConverterService;
    }

    /**
     * @param PlaytimePerMonthApiService $apiService
     * @param UserInterface $user
     * @return JsonResponse
     */
    public function sessionsPerMonth(PlaytimePerMonthApiService $apiService, UserInterface $user): JsonResponse
    {
        return $apiService->getSessionsPerMonth($user);
    }

    /**
     * @param PlaytimePerMonthApiService $apiService
     * @param UserInterface $user
     * @return JsonResponse
     */
    public function averagePerMonth(PlaytimePerMonthApiService $apiService, UserInterface $user): JsonResponse
    {
        return $apiService->getAveragePlaytimePerMonth($user);
    }

    /**
     * @param GameSessionRepository $gameSessionRepository
     * @param UserInterface $user
     * @return JsonResponse
     */
    public function sessionsLastDays(GameSessionRepository $gameSessionRepository, UserInterface $user): JsonResponse
    {
        $sessions = $gameSessionRepository->findForLastDays($user);

        return new JsonResponse($this->mapSessionData($sessions));
    }

    /**
     * @param int $id
     * @param GameRepository $gameRepository
     * @param GameSessionRepository $sessionRepository
     * @param UserInterface $user
     * @return JsonResponse
     */
    public function sessionsForGame(
        int $id,
        GameRepository $gameRepository,
        GameSessionRepository $sessionRepository,
        UserInterface $user
    ): JsonResponse {
        /**
         * @var Game $game
         */
        $game = $gameRepository->find($id);

        if (!$game) {
            throw new NotFoundHttpException();
        }
        $sessions = $sessionRepository->findBy(['game' => $game, 'steamUserId' => $user->getSteamId()]);

        $data = [];

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
     * @param int $id
     * @param GameRepository $gameRepository
     * @param GameSessionsPerMonthRepository $gameSessionsPerMonthRepository
     * @param UserInterface $user
     * @return JsonResponse
     */
    public function sessionsPerMonthForGame(
        int $id,
        GameRepository $gameRepository,
        GameSessionsPerMonthRepository $gameSessionsPerMonthRepository,
        UserInterface $user
    ): JsonResponse {
        $game = $gameRepository->find($id);
        if ($game === null) {
            throw new NotFoundHttpException();
        }

        $sessions = $gameSessionsPerMonthRepository->findBy(['game' => $game, 'steamUserId' => $user->getSteamId()]);
        $data = [];

        /**
         * @var GameSession $session
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

    /**
     * @param GameSessionRepository $sessionRepository
     * @param UserInterface $user
     * @return JsonResponse
     */
    public function sessionsThisYear(GameSessionRepository $sessionRepository, UserInterface $user): JsonResponse
    {
        $sessions = $sessionRepository->findForThisYear($user);

        return new JsonResponse($this->mapSessionData($sessions));
    }

    /**
     * @param int $year
     * @param GameSessionRepository $sessionRepository
     * @param UserInterface $user
     * @return JsonResponse
     */
    public function sessionsForYear(
        int $year,
        GameSessionRepository $sessionRepository,
        UserInterface $user
    ): JsonResponse {
        $sessions = $sessionRepository->findForYear($year, $user);

        return new JsonResponse($this->mapSessionData($sessions));
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
