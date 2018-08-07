<?php

namespace App\Twig;

use App\Entity\Game;
use App\Entity\Purchase;
use App\Entity\User;
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
            new TwigFilter('get_overall_costs', [$this, 'getOverallCosts']),
            new TwigFilter('get_costs_per_hour', [$this, 'getCostsPerHour']),
        ];
    }

    /**
     * @param Game $game
     * @param User $user
     * @return float
     */
    public function getOverallCosts(Game $game, User $user): float
    {
        return $this->purchaseUtil->generateOverallCosts($game, $user);
    }

    /**
     * @param Game $game
     * @param User $user
     * @return float
     */
    public function getCostsPerHour(Game $game, User $user): float
    {
        return $this->purchaseUtil->generateCostsPerHour($game, $user);
    }
}
