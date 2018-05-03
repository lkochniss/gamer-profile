<?php

namespace tests\App\Service\Stats;

use App\Entity\Game;
use App\Entity\OverallGameStats;
use App\Entity\Purchase;
use App\Repository\OverallGameStatsRepository;
use App\Service\Stats\WastedMoneyService;
use App\Service\Util\PurchaseUtil;
use PHPUnit\Framework\TestCase;

/**
 * Class WastedMoneyServiceTest
 */
class WastedMoneyServiceTest extends TestCase
{
    public function testAddPurchaseWithGameBelowThreshold(): void
    {
        $expectedOverallGameStats = new OverallGameStats();
        $expectedOverallGameStats->addToWastedMoney(100);

        $overallGameStatsRepositoryMock = $this->createMock(OverallGameStatsRepository::class);
        $overallGameStatsRepositoryMock->expects($this->any())
            ->method('findOneBy')
            ->with(['identifier' => getenv('STEAM_USER_ID')])
            ->willReturn(new OverallGameStats());

        $overallGameStatsRepositoryMock->expects($this->any())
            ->method('save')
            ->with($expectedOverallGameStats);

        $purchaseUtilMock = $this->createMock(PurchaseUtil::class);
        $purchaseUtilMock->expects($this->any())
            ->method('transformPrice')
            ->with(
                $this->equalTo(100.0),
                $this->equalTo(getenv('DEFAULT_CURRENCY')),
                $this->equalTo(getenv('DEFAULT_CURRENCY'))
            )
            ->willReturn(100.0);

        $wastedMoneyService = new WastedMoneyService(
            $purchaseUtilMock,
            $overallGameStatsRepositoryMock
        );

        $game = new Game();
        $game->setTimePlayed(20);

        $purchase = new Purchase();
        $purchase->setCurrency(getenv('DEFAULT_CURRENCY'));
        $purchase->setPrice(100);
        $purchase->setGame($game);

        $this->assertEquals(
            $expectedOverallGameStats,
            $wastedMoneyService->addPurchase($purchase)
        );
    }

    public function testAddPurchaseWithGameAboveThreshold(): void
    {
        $expectedOverallGameStats = new OverallGameStats();

        $overallGameStatsRepositoryMock = $this->createMock(OverallGameStatsRepository::class);
        $overallGameStatsRepositoryMock->expects($this->any())
            ->method('findOneBy')
            ->with(['identifier' => getenv('STEAM_USER_ID')])
            ->willReturn(new OverallGameStats());

        $overallGameStatsRepositoryMock->expects($this->any())
            ->method('save')
            ->with($expectedOverallGameStats);

        $purchaseUtilMock = $this->createMock(PurchaseUtil::class);
        $purchaseUtilMock->expects($this->never())
            ->method('transformPrice');

        $wastedMoneyServiceTest = new WastedMoneyService(
            $purchaseUtilMock,
            $overallGameStatsRepositoryMock
        );

        $game = new Game();
        $game->setTimePlayed(60);

        $purchase = new Purchase();
        $purchase->setCurrency(getenv('DEFAULT_CURRENCY'));
        $purchase->setPrice(100);
        $purchase->setGame($game);

        $this->assertEquals(
            $expectedOverallGameStats,
            $wastedMoneyServiceTest->addPurchase($purchase)
        );
    }

    public function testUpdatePurchaseBelowThreshold(): void
    {
        $expectedOverallGameStats = new OverallGameStats();
        $expectedOverallGameStats->addToWastedMoney(100);

        $oldOverallGameStats = new OverallGameStats();
        $oldOverallGameStats->addToWastedMoney(50);
        $overallGameStatsRepositoryMock = $this->createMock(OverallGameStatsRepository::class);
        $overallGameStatsRepositoryMock->expects($this->any())
            ->method('findOneBy')
            ->with(['identifier' => getenv('STEAM_USER_ID')])
            ->willReturn($oldOverallGameStats);

        $overallGameStatsRepositoryMock->expects($this->any())
            ->method('save')
            ->with($expectedOverallGameStats);

        $purchaseUtilMock = $this->createMock(PurchaseUtil::class);
        $purchaseUtilMock->expects($this->any())
            ->method('transformPrice')
            ->with(
                $this->equalTo(50.0),
                $this->equalTo(getenv('DEFAULT_CURRENCY')),
                $this->equalTo(getenv('DEFAULT_CURRENCY'))
            )
            ->willReturn(50.0);

        $wastedMoneyService = new WastedMoneyService(
            $purchaseUtilMock,
            $overallGameStatsRepositoryMock
        );

        $game = new Game();
        $game->setTimePlayed(20);

        $purchase = new Purchase();
        $purchase->setCurrency(getenv('DEFAULT_CURRENCY'));
        $purchase->setPrice(100);
        $purchase->setGame($game);

        $this->assertEquals(
            $expectedOverallGameStats,
            $wastedMoneyService->updatePurchase(50, $purchase)
        );
    }

    public function testUpdatePurchaseAboveThreshold(): void
    {
        $expectedOverallGameStats = new OverallGameStats();
        $expectedOverallGameStats->addToWastedMoney(50);

        $oldOverallGameStats = new OverallGameStats();
        $oldOverallGameStats->addToWastedMoney(50);
        $overallGameStatsRepositoryMock = $this->createMock(OverallGameStatsRepository::class);
        $overallGameStatsRepositoryMock->expects($this->any())
            ->method('findOneBy')
            ->with(['identifier' => getenv('STEAM_USER_ID')])
            ->willReturn($oldOverallGameStats);

        $overallGameStatsRepositoryMock->expects($this->any())
            ->method('save')
            ->with($expectedOverallGameStats);

        $purchaseUtilMock = $this->createMock(PurchaseUtil::class);
        $purchaseUtilMock->expects($this->never())
            ->method('transformPrice');

        $wastedMoneyService = new WastedMoneyService(
            $purchaseUtilMock,
            $overallGameStatsRepositoryMock
        );

        $game = new Game();
        $game->setTimePlayed(60);

        $purchase = new Purchase();
        $purchase->setCurrency(getenv('DEFAULT_CURRENCY'));
        $purchase->setPrice(100);
        $purchase->setGame($game);

        $this->assertEquals(
            $expectedOverallGameStats,
            $wastedMoneyService->updatePurchase(50, $purchase)
        );
    }

    public function testRemovePurchase(): void
    {
        $expectedOverallGameStats = new OverallGameStats();
        $expectedOverallGameStats->addToWastedMoney(50);

        $oldOverallGameStats = new OverallGameStats();
        $oldOverallGameStats->addToWastedMoney(100);
        $overallGameStatsRepositoryMock = $this->createMock(OverallGameStatsRepository::class);
        $overallGameStatsRepositoryMock->expects($this->any())
            ->method('findOneBy')
            ->with(['identifier' => getenv('STEAM_USER_ID')])
            ->willReturn($oldOverallGameStats);

        $overallGameStatsRepositoryMock->expects($this->any())
            ->method('save')
            ->with($expectedOverallGameStats);

        $purchaseUtilMock = $this->createMock(PurchaseUtil::class);
        $purchaseUtilMock->expects($this->any())
            ->method('transformPrice')
            ->with(
                $this->equalTo(-50.0),
                $this->equalTo(getenv('DEFAULT_CURRENCY')),
                $this->equalTo(getenv('DEFAULT_CURRENCY'))
            )
            ->willReturn(-50.0);

        $wastedMoneyService = new WastedMoneyService(
            $purchaseUtilMock,
            $overallGameStatsRepositoryMock
        );

        $purchase = new Purchase();
        $purchase->setCurrency(getenv('DEFAULT_CURRENCY'));
        $purchase->setPrice(50);

        $this->assertEquals(
            $expectedOverallGameStats,
            $wastedMoneyService->removePurchase($purchase)
        );
    }

    public function testAddGameDefaultPriceAboveThreshold(): void
    {
        $expectedOverallGameStats = new OverallGameStats();
        $overallGameStatsRepositoryMock = $this->createMock(OverallGameStatsRepository::class);
        $overallGameStatsRepositoryMock->expects($this->any())
            ->method('findOneBy')
            ->with(['identifier' => getenv('STEAM_USER_ID')])
            ->willReturn(new OverallGameStats());

        $overallGameStatsRepositoryMock->expects($this->any())
            ->method('save')
            ->with($expectedOverallGameStats);

        $purchaseUtilMock = $this->createMock(PurchaseUtil::class);
        $purchaseUtilMock->expects($this->never())
            ->method('transformPrice');

        $wastedMoneyService = new WastedMoneyService(
            $purchaseUtilMock,
            $overallGameStatsRepositoryMock
        );

        $game = new Game();
        $game->setPrice(50);
        $game->setTimePlayed(60);

        $this->assertEquals(
            $expectedOverallGameStats,
            $wastedMoneyService->addGameDefaultPrice($game)
        );
    }

    public function testAddGameDefaultPriceWithGamePurchase(): void
    {
        $expectedOverallGameStats = new OverallGameStats();
        $overallGameStatsRepositoryMock = $this->createMock(OverallGameStatsRepository::class);
        $overallGameStatsRepositoryMock->expects($this->any())
            ->method('findOneBy')
            ->with(['identifier' => getenv('STEAM_USER_ID')])
            ->willReturn(new OverallGameStats());

        $overallGameStatsRepositoryMock->expects($this->any())
            ->method('save')
            ->with($expectedOverallGameStats);

        $purchaseUtilMock = $this->createMock(PurchaseUtil::class);
        $purchaseUtilMock->expects($this->never())
            ->method('transformPrice');

        $wastedMoneyService = new WastedMoneyService(
            $purchaseUtilMock,
            $overallGameStatsRepositoryMock
        );

        $purchase = new Purchase();
        $purchase->setType(Purchase::GAME_PURCHASE);

        $game = new Game();
        $game->setPrice(50);
        $game->setTimePlayed(20);
        $game->addPurchase($purchase);

        $this->assertEquals(
            $expectedOverallGameStats,
            $wastedMoneyService->addGameDefaultPrice($game)
        );
    }
}
