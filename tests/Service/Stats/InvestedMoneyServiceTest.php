<?php

namespace tests\App\Service\Stats;

use App\Entity\Game;
use App\Entity\OverallGameStats;
use App\Entity\Purchase;
use App\Repository\OverallGameStatsRepository;
use App\Service\Stats\InvestedMoneyService;
use App\Service\Util\PurchaseUtil;
use PHPUnit\Framework\TestCase;

/**
 * Class InvestedMoneyServiceTest
 */
class InvestedMoneyServiceTest extends TestCase
{
    public function testAddPurchase(): void
    {
        $expectedOverallGameStats = new OverallGameStats();
        $expectedOverallGameStats->addToInvestedMoney(100);

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

        $investedMoneyService = new InvestedMoneyService(
            $purchaseUtilMock,
            $overallGameStatsRepositoryMock
        );

        $purchase = new Purchase();
        $purchase->setCurrency(getenv('DEFAULT_CURRENCY'));
        $purchase->setPrice(100);

        $this->assertEquals(
            $expectedOverallGameStats,
            $investedMoneyService->addPurchase($purchase)
        );
    }

    public function testUpdatePurchase(): void
    {
        $expectedOverallGameStats = new OverallGameStats();
        $expectedOverallGameStats->addToInvestedMoney(100);

        $oldOverallGameStats = new OverallGameStats();
        $oldOverallGameStats->addToInvestedMoney(50);
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

        $investedMoneyService = new InvestedMoneyService(
            $purchaseUtilMock,
            $overallGameStatsRepositoryMock
        );

        $purchase = new Purchase();
        $purchase->setCurrency(getenv('DEFAULT_CURRENCY'));
        $purchase->setPrice(100);

        $this->assertEquals(
            $expectedOverallGameStats,
            $investedMoneyService->updatePurchase(50, $purchase)
        );
    }

    public function testAddGameDefaultPriceWithoutGamePurchase(): void
    {
        $expectedOverallGameStats = new OverallGameStats();
        $expectedOverallGameStats->addToInvestedMoney(50);

        $overallGameStatsRepositoryMock = $this->createMock(OverallGameStatsRepository::class);
        $overallGameStatsRepositoryMock->expects($this->any())
            ->method('findOneBy')
            ->with(['identifier' => getenv('STEAM_USER_ID')])
            ->willReturn(new OverallGameStats());

        $overallGameStatsRepositoryMock->expects($this->any())
            ->method('save')
            ->with($expectedOverallGameStats);

        $game = new Game();
        $game->setPrice(50);
        $game->setCurrency(getenv('DEFAULT_CURRENCY'));

        $purchaseUtilMock = $this->createMock(PurchaseUtil::class);

        $purchaseUtilMock->expects($this->any())
            ->method('transformPrice')
            ->with(
                $this->equalTo(50.0),
                $this->equalTo(getenv('DEFAULT_CURRENCY')),
                $this->equalTo(getenv('DEFAULT_CURRENCY'))
            )
            ->willReturn(50.0);

        $investedMoneyService = new InvestedMoneyService(
            $purchaseUtilMock,
            $overallGameStatsRepositoryMock
        );

        $this->assertEquals(
            $expectedOverallGameStats,
            $investedMoneyService->addGameDefaultPrice($game)
        );
    }

    public function testUpdateGameDefaultPriceWithoutGamePurchase(): void
    {
        $expectedOverallGameStats = new OverallGameStats();
        $expectedOverallGameStats->addToInvestedMoney(100);

        $oldOverallGameStats = new OverallGameStats();
        $oldOverallGameStats->addToInvestedMoney(50);

        $overallGameStatsRepositoryMock = $this->createMock(OverallGameStatsRepository::class);
        $overallGameStatsRepositoryMock->expects($this->any())
            ->method('findOneBy')
            ->with(['identifier' => getenv('STEAM_USER_ID')])
            ->willReturn($oldOverallGameStats);

        $overallGameStatsRepositoryMock->expects($this->any())
            ->method('save')
            ->with($expectedOverallGameStats);

        $game = new Game();
        $game->setPrice(100);
        $game->setCurrency(getenv('DEFAULT_CURRENCY'));

        $purchaseUtilMock = $this->createMock(PurchaseUtil::class);

        $purchaseUtilMock->expects($this->any())
            ->method('transformPrice')
            ->with(
                $this->equalTo(50.0),
                $this->equalTo(getenv('DEFAULT_CURRENCY')),
                $this->equalTo(getenv('DEFAULT_CURRENCY'))
            )
            ->willReturn(50.0);

        $investedMoneyService = new InvestedMoneyService(
            $purchaseUtilMock,
            $overallGameStatsRepositoryMock
        );

        $this->assertEquals(
            $expectedOverallGameStats,
            $investedMoneyService->updateGameDefaultPrice(50, $game)
        );
    }

    public function testUpdateGameDefaultPriceWithGamePurchase(): void
    {
        $expectedOverallGameStats = new OverallGameStats();
        $expectedOverallGameStats->addToInvestedMoney(50);

        $overallGameStatsRepositoryMock = $this->createMock(OverallGameStatsRepository::class);
        $overallGameStatsRepositoryMock->expects($this->any())
            ->method('findOneBy')
            ->with(['identifier' => getenv('STEAM_USER_ID')])
            ->willReturn($expectedOverallGameStats);

        $overallGameStatsRepositoryMock->expects($this->any())
            ->method('save')
            ->with($expectedOverallGameStats);

        $purchase = new Purchase();
        $purchase->setType(Purchase::GAME_PURCHASE);

        $game = new Game();
        $game->setPrice(100);
        $game->setCurrency(getenv('DEFAULT_CURRENCY'));
        $game->addPurchase($purchase);

        $purchaseUtilMock = $this->createMock(PurchaseUtil::class);

        $purchaseUtilMock->expects($this->never())
            ->method('transformPrice');

        $investedMoneyService = new InvestedMoneyService(
            $purchaseUtilMock,
            $overallGameStatsRepositoryMock
        );

        $this->assertEquals(
            $expectedOverallGameStats,
            $investedMoneyService->updateGameDefaultPrice(50, $game)
        );
    }
}
