<?php

namespace App\Twig;

use App\Entity\Game;
use App\Service\TimeConverterService;
use App\Service\Util\PurchaseUtil;
use App\Service\Transformation\TimeTransformation;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class AppExtension
 */
class AppExtension extends AbstractExtension
{
    /**
     * @var TimeConverterService
     */
    private $timeConverterService;

    /**
     * AppExtension constructor.
     * @param TimeConverterService $timeConverterService
     */
    public function __construct(TimeConverterService $timeConverterService)
    {
        $this->timeConverterService = $timeConverterService;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('convert_overall_time', [$this, 'convertOverallTime']),
            new TwigFilter('convert_recent_time', [$this, 'convertRecentTime']),
            new TwigFilter('get_game_overall_costs', [$this, 'getGameOverallCosts']),
            new TwigFilter('get_game_costs_per_hour', [$this, 'getGameCostsPerHour'])
        ];
    }

    /**
     * @param int $value
     * @return string
     */
    public function convertRecentTime(int $value): string
    {
        return $this->timeConverterService->convertRecentTime($value);
    }

    /**
     * @param int $value
     * @return string
     */
    public function convertOverallTime(int $value): string
    {
        return $this->timeConverterService->convertOverallTime($value);
    }

    /**
     * @param Game $game
     * @return float
     */
    public function getGameOverallCosts(Game $game): float
    {
        $purchaseService  = new PurchaseUtil();

        return $purchaseService->generateOverallCosts($game);
    }

    /**
     * @param Game $game
     * @return float
     */
    public function getGameCostsPerHour(Game $game): float
    {
        $purchaseService  = new PurchaseUtil();

        return $purchaseService->generateCostsPerHour($game);
    }
}
