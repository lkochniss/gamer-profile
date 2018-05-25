<?php

namespace App\Controller;

use App\Repository\PlaytimePerMonthRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class ApiController
 */
class ApiController extends Controller
{
    public function sessionsPerMonth(PlaytimePerMonthRepository $playtimePerMonthRepository)
    {
        $playtimePerMonth = $playtimePerMonthRepository->findAll();

        $data = [];

        foreach ($playtimePerMonth as $playtime) {
            $data[] = [
                'total' => $playtime->getDuration(),
                'month' => $playtime->getMonth()->format('F Y')
            ];
        }


        return new JsonResponse($data);
    }
}
