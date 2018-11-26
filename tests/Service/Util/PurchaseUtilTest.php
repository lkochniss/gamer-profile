<?php

namespace tests\App\Service\Util;

use App\Entity\Game;
use App\Entity\Purchase;
use App\Service\Util\PurchaseUtil;
use PHPUnit\Framework\TestCase;

/**
 * Class PurchaseUtilTest
 */
class PurchaseUtilTest extends TestCase
{

    public function testOverallCostsForGameWithDlcPurchases(): void
    {
        $purchase = new Purchase();
        $purchase->setPrice(2);

        $game = new Game();
        $game->addPurchase($purchase);

        $purchaseService = new PurchaseUtil();

        $this->assertEquals(2, $purchaseService->generateOverallCosts($game));
    }

    public function testOverallCostsForGameWithoutInitialPriceAndWithoutPurchases(): void
    {
        $game = new Game();
        $purchaseService = new PurchaseUtil();

        $this->assertEquals(0, $purchaseService->generateOverallCosts($game));
    }

    public function testOverallCostsForGameWithoutInitialPriceAndDlcPurchases(): void
    {
        $purchase = new Purchase();
        $purchase->setPrice(2);

        $game = new Game();
        $game->addPurchase($purchase);

        $purchaseService = new PurchaseUtil();

        $this->assertEquals(2, $purchaseService->generateOverallCosts($game));
    }

    public function testCostPerHourWithOneMinuteForGameWithPurchases(): void
    {
        $purchase = new Purchase();
        $purchase->setPrice(2);

        $game = new Game();
        $game->setTimePlayed(1);
        $game->addPurchase($purchase);

        $purchaseService = new PurchaseUtil();

        $this->assertEquals(2, $purchaseService->generateCostsPerHour($game));
    }

    public function testCostPerHourWithOneHourForGameWithInitialPriceAndWithoutPurchases(): void
    {
        $gamePurchase = new Purchase();
        $gamePurchase->setPrice(3);

        $dlcPurchase = new Purchase();
        $dlcPurchase->setPrice(1);
        $dlcPurchase->setType(Purchase::DLC_PURCHASE);

        $game = new Game();
        $game->setTimePlayed(60);
        $game->addPurchase($gamePurchase);
        $game->addPurchase($dlcPurchase);

        $purchaseService = new PurchaseUtil();

        $this->assertEquals(4, $purchaseService->generateCostsPerHour($game));
    }

    public function testCostPerHourWithEightyMinutesForGameWithPurchases(): void
    {
        $purchase = new Purchase();
        $purchase->setPrice(2);

        $game = new Game();
        $game->setTimePlayed(80);
        $game->addPurchase($purchase);

        $purchaseService = new PurchaseUtil();

        $this->assertEquals(1.5, $purchaseService->generateCostsPerHour($game));
    }
}
