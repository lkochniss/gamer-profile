<?php

namespace App\Twig;

use App\Entity\Game;
use App\Service\Util\PurchaseUtil;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class PurchaseExtension
 */
class PurchaseExtension extends AbstractExtension
{

    /**
     * @var PurchaseUtil
     */
    private $purchaseUtil;

    /**
     * PurchaseExtension constructor.
     * @param PurchaseUtil $purchaseUtil
     */
    public function __construct(PurchaseUtil $purchaseUtil)
    {
        $this->purchaseUtil = $purchaseUtil;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('get_game_overall_costs', [$this, 'getGameOverallCosts']),
            new TwigFilter('get_game_costs_per_hour', [$this, 'getGameCostsPerHour'])
        ];
    }

    /**
     * @param Game $game
     * @return float
     */
    public function getGameOverallCosts(Game $game): float
    {
        return $this->purchaseUtil->generateOverallCosts($game);
    }

    /**
     * @param Game $game
     * @return float
     */
    public function getGameCostsPerHour(Game $game): float
    {
        return $this->purchaseUtil->generateCostsPerHour($game);
    }
}
