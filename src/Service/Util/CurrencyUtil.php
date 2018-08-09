<?php

namespace App\Service\Util;

/**
 * Class CurrencyUtil
 */
class CurrencyUtil
{
    /**
     * @param float $price
     * @param string $fromCurrency
     * @param string $toCurrency
     * @return float
     */
    public static function transformPrice(float $price, string $fromCurrency, string $toCurrency): float
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
