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

        $jan = new \DateTime('-4 month');
        $feb = new \DateTime('-3 month');
        $mar = new \DateTime('-2 month');
        $apr = new \DateTime('-1 month');
        $data = [
            [
                'total' => 1234,
                'month' => $jan->format('m-y')
            ],
            [
                'total' => 1500,
                'month' => $feb->format('m-y')
            ],
            [
                'total' => 1432,
                'month' => $mar->format('m-y')
            ],
            [
                'total' => 1200,
                'month' => $apr->format('m-y')
            ]
        ];

        foreach ($playtimePerMonth as $playtime) {
            $data[] = [
                'total' => $playtime->getDuration(),
                'month' => $playtime->getMonth()->format('m-y')
            ];
        }


        return new JsonResponse($data);
    }
}
