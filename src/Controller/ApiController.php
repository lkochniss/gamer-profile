<?php

namespace App\Controller;

use App\Service\Api\GameSessionApiService;
use App\Service\Api\GameSessionsPerMonthApiService;
use App\Service\Api\PlaytimePerMonthApiService;
use App\Service\Util\TimeConverterUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @param GameSessionApiService $apiService
     * @param UserInterface $user
     * @return JsonResponse
     */
    public function sessionsLastDays(GameSessionApiService $apiService, UserInterface $user): JsonResponse
    {
        return $apiService->getSessionsLastDays($user);
    }

    /**
     * @param int $id
     * @param GameSessionApiService $apiService
     * @param UserInterface $user
     * @return JsonResponse
     */
    public function sessionsForGame(
        int $id,
        GameSessionApiService $apiService,
        UserInterface $user
    ): JsonResponse {
        return $apiService->getSessionsForGame($id, $user);
    }

    /**
     * @param int $id
     * @param GameSessionsPerMonthApiService $apiService
     * @param UserInterface $user
     * @return JsonResponse
     */
    public function sessionsPerMonthForGame(
        int $id,
        GameSessionsPerMonthApiService $apiService,
        UserInterface $user
    ): JsonResponse {
        return $apiService->getSessionsPerMonthForGame($id, $user);
    }

    /**
     * @param GameSessionApiService $apiService
     * @param UserInterface $user
     * @return JsonResponse
     */
    public function sessionsThisYear(GameSessionApiService $apiService, UserInterface $user): JsonResponse
    {
        return $apiService->getSessionsThisYear($user);
    }

    /**
     * @param int $year
     * @param GameSessionApiService $apiService
     * @param UserInterface $user
     * @return JsonResponse
     */
    public function sessionsForYear(int $year, GameSessionApiService $apiService, UserInterface $user): JsonResponse
    {
        return $apiService->getSessionsForYear($year, $user);
    }
}
