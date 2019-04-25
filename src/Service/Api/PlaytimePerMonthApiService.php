<?php

namespace App\Service\Api;

use App\Entity\User;
use App\Repository\PlaytimePerMonthRepository;
use App\Service\Transformation\PlaytimePerMonthTransformation;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class PlaytimePerMonthApiService
 */
class PlaytimePerMonthApiService
{
    /**
     * @var PlaytimePerMonthTransformation
     */
    private $playtimeApiService;

    /**
     * @var PlaytimePerMonthRepository
     */
    private $playtimePerMonthRepository;

    /**
     * PlaytimePerMonthApiService constructor.
     * @param PlaytimePerMonthTransformation $playtimeApiService
     * @param PlaytimePerMonthRepository $playtimePerMonthRepository
     */
    public function __construct(
        PlaytimePerMonthTransformation $playtimeApiService,
        PlaytimePerMonthRepository $playtimePerMonthRepository
    ) {
        $this->playtimeApiService = $playtimeApiService;
        $this->playtimePerMonthRepository = $playtimePerMonthRepository;
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function getSessionsPerMonth(User $user): JsonResponse
    {
        $playtimePerMonth = $this->playtimePerMonthRepository->findBy([
            'steamUserId' => $user->getSteamId()
        ]);

        $data = [];
        foreach ($playtimePerMonth as $playtime) {
            $data[] = $this->playtimeApiService->getPlaytimeResponse($playtime);
        }

        return new JsonResponse($data);
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function getAveragePlaytimePerMonth(User $user): JsonResponse
    {
        $playtimePerMonth = $this->playtimePerMonthRepository->findBy(['steamUserId' => $user->getSteamId()]);

        $data = [];
        foreach ($playtimePerMonth as $playtime) {
            $data[] = $this->playtimeApiService->getAveragePlaytimeResponse($playtime);
        }

        return new JsonResponse($data);
    }
}
