<?php

namespace tests\App\Service\Steam;

use App\Entity\Game;
use App\Entity\Purchase;
use App\Service\PurchaseService;
use PHPUnit\Framework\TestCase;

/**
 * Class ReportServiceTest
 */
class PurchaseServiceTest extends TestCase
{
    public function testOverallCostsForGameWithInitialPriceAndWithoutPurchases(): void
    {
        $game = new Game();
        $game->setPrice(1);
        $game->setCurrency('USD');

        $purchaseService = new PurchaseService();

        $this->assertEquals(1, $purchaseService->generateOverallCosts($game));
    }

    public function testOverallCostsForGameWithInitialPriceAndDlcPurchases(): void
    {
        $purchase = new Purchase();
        $purchase->setPrice(2);
        $purchase->setCurrency('USD');
        $purchase->setType('dlc-purchase');
        $game = new Game();
        $game->setPrice(1);
        $game->setCurrency('USD');
        $game->addPurchase($purchase);

        $purchaseService = new PurchaseService();

        $this->assertEquals(3, $purchaseService->generateOverallCosts($game));
    }

    public function testOverallCostsForGameWithInitialPriceAndGamePurchases(): void
    {
        $purchase = new Purchase();
        $purchase->setPrice(2);
        $purchase->setCurrency('USD');
        $purchase->setType('game-purchase');
        $game = new Game();
        $game->setPrice(1);
        $game->setCurrency('USD');
        $game->addPurchase($purchase);

        $purchaseService = new PurchaseService();

        $this->assertEquals(2, $purchaseService->generateOverallCosts($game));
    }

    public function testOverallCostsForGameWithoutInitialPriceAndWithoutPurchases(): void
    {
        $game = new Game();

        $purchaseService = new PurchaseService();

        $this->assertEquals(0, $purchaseService->generateOverallCosts($game));
    }

    public function testOverallCostsForGameWithoutInitialPriceAndDlcPurchases(): void
    {
        $purchase = new Purchase();
        $purchase->setPrice(2);
        $purchase->setCurrency('USD');
        $purchase->setType('dlc-purchase');

        $game = new Game();
        $game->addPurchase($purchase);

        $purchaseService = new PurchaseService();

        $this->assertEquals(2, $purchaseService->generateOverallCosts($game));
    }

    public function testOverallCostsForGameWithInitialEurPriceAndUsdDlcPurchases(): void
    {
        $purchase = new Purchase();
        $purchase->setPrice(2);
        $purchase->setCurrency('USD');
        $purchase->setType('dlc-purchase');
        $game = new Game();
        $game->setPrice(1);
        $game->setCurrency('EUR');
        $game->addPurchase($purchase);

        $purchaseService = new PurchaseService();

        $this->assertEquals(2.65, $purchaseService->generateOverallCosts($game));
    }

    public function testOverallCostsForGameWithInitialUsdPriceAndEurDlcPurchases(): void
    {
        $purchase = new Purchase();
        $purchase->setPrice(2);
        $purchase->setCurrency('EUR');
        $purchase->setType('dlc-purchase');
        $game = new Game();
        $game->setPrice(1);
        $game->setCurrency('USD');
        $game->addPurchase($purchase);

        $purchaseService = new PurchaseService();

        $this->assertEquals(3.43, $purchaseService->generateOverallCosts($game));
    }

    public function testCostPerHourWithOneMinuteForGameWithInitialPriceAndWithoutPurchases(): void
    {
        $game = new Game();
        $game->setPrice(1);
        $game->setCurrency('USD');
        $game->setTimePlayed(1);

        $purchaseService = new PurchaseService();

        $this->assertEquals(1, $purchaseService->generateCostsPerHour($game));
    }

    public function testCostPerHourWithOneHourForGameWithInitialPriceAndWithoutPurchases(): void
    {
        $game = new Game();
        $game->setPrice(1);
        $game->setCurrency('USD');
        $game->setTimePlayed(60);

        $purchaseService = new PurchaseService();

        $this->assertEquals(1, $purchaseService->generateCostsPerHour($game));
    }

    public function testCostPerHourWithEightyMinutesForGameWithInitialPriceAndWithoutPurchases(): void
    {
        $game = new Game();
        $game->setPrice(1);
        $game->setCurrency('USD');
        $game->setTimePlayed(80);

        $purchaseService = new PurchaseService();

        $this->assertEquals(0.75, $purchaseService->generateCostsPerHour($game));
    }
}
