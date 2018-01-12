<?php

namespace App\Service;

use App\Entity\Game;

/**
 * Class PurchaseService
 */
class PurchaseService
{
    /**
     * @param Game $game
     * @return float
     */
    public function generateOverallCosts(Game $game): float
    {
        $sum = $game->getPrice();

        foreach ($game->getPurchases() as $purchase) {
            $sum += $this->transformPrice($purchase->getPrice(), $purchase->getCurrency(), $game->getCurrency());
            if ($purchase->getType() === 'game-purchase') {
                $sum = $sum - $game->getPrice();
            }
        }

        return $sum;
    }

    /**
     * @param Game $game
     * @return float
     */
    public function generateCostsPerHour(Game $game): float
    {
        $time = $game->getTimePlayed() > 60 ? $game->getTimePlayed() : 60;

        return round($this->generateOverallCosts($game) / ($time / 60), 2);
    }

    /**
     * @param float $price
     * @param string $fromCurrency
     * @param string $toCurrency
     * @return float
     */
    private function transformPrice(float $price, string $fromCurrency, string $toCurrency): float
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
