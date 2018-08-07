<?php

namespace App\Service\Util;

use App\Entity\Game;
use App\Entity\Purchase;
use App\Entity\User;
use App\Repository\PlaytimeRepository;
use App\Repository\PurchaseRepository;

/**
 * Class PurchaseUtil
 */
class PurchaseUtil
{
    /**
     * @var PurchaseRepository
     */
    private $purchaseRepository;

    /**
     * @var PlaytimeRepository
     */
    private $playtimeRepository;

    /**
     * PurchaseUtil constructor.
     * @param PurchaseRepository $purchaseRepository
     * @param PlaytimeRepository $playtimeRepository
     */
    public function __construct(PurchaseRepository $purchaseRepository, PlaytimeRepository $playtimeRepository)
    {
        $this->purchaseRepository = $purchaseRepository;
        $this->playtimeRepository = $playtimeRepository;
    }

    /**
     * @param Game $game
     * @param User $user
     * @return float
     */
    public function generateOverallCosts(Game $game, User $user): float
    {
        $sum = 0;

        $purchases = $this->purchaseRepository->findBy(['game' => $game, 'user' => $user]);

        foreach ($purchases as $purchase) {
            $sum += $this->transformPrice($purchase->getPrice(), $purchase->getCurrency(), $game->getCurrency());
        }

        return $sum;
    }

    /**
     * @param Game $game
     * @param User $user
     * @return float
     */
    public function generateCostsPerHour(Game $game, User $user): float
    {
        $playtime = $this->playtimeRepository->findOneBy(['game' => $game, 'user' => $user]);

        if (is_null($playtime)) {
            return 0.0;
        }

        $time = $playtime->getOverallPlaytime() > 60 ? $playtime->getOverallPlaytime() : 60;

        return round($this->generateOverallCosts($game, $user) / ($time / 60), 2);
    }

    /**
     * @param float $price
     * @param string $fromCurrency
     * @param string $toCurrency
     * @return float
     */
    public function transformPrice(float $price, string $fromCurrency, string $toCurrency): float
    {
        if ($fromCurrency === 'USD' && $toCurrency === 'EUR') {
            $price *= 0.824;
        }

        if ($fromCurrency === 'EUR' && $toCurrency === 'USD') {
            $price *= 1.213;
        }

        return round($price, 2);
    }
}
