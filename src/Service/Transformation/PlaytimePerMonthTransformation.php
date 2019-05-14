<?php

namespace App\Service\Transformation;

use App\Entity\PlaytimePerMonth;
use App\Service\Util\TimeConverterUtil;

/**
 * Class PlaytimePerMonthTransformation
 */
class PlaytimePerMonthTransformation
{
    /**
     * @var TimeConverterUtil
     */
    private $timeConverterService;

    /**
     * PlaytimePerMonthApiService constructor.
     * @param TimeConverterUtil $timeConverterService
     */
    public function __construct(TimeConverterUtil $timeConverterService)
    {
        $this->timeConverterService = $timeConverterService;
    }

    /**
     * @param PlaytimePerMonth $playtime
     * @return array
     */
    public function getPlaytimeResponse(PlaytimePerMonth $playtime): array
    {
        return [
            'date' => $playtime->getMonth()->format('M Y'),
            'timeInMinutes' => $playtime->getDuration(),
            'timeForTooltip' => $this->timeConverterService->convertOverallTime($playtime->getDuration())
        ];
    }

    /**
     * @param PlaytimePerMonth $playtime
     * @return array
     */
    public function getAveragePlaytimeResponse(PlaytimePerMonth $playtime): array
    {
        $lastDayOfMonth = new \DateTime(' last day of ' . $playtime->getMonth()->format('M Y'));

        $today = new \DateTime();
        if ($today->format('M-Y') == $lastDayOfMonth->format('M-Y')) {
            $lastDayOfMonth = $today;
        }

        $average = round($playtime->getDuration() / $lastDayOfMonth->format('d'), 0);
        return [
            'date' => $playtime->getMonth()->format('M Y'),
            'timeInMinutes' => $average,
            'timeForTooltip' => $this->timeConverterService->convertRecentTime($average)
        ];
    }
}
