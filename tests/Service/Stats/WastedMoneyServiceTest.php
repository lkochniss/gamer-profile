<?php

namespace tests\App\Service\Stats;

use App\Entity\Game;
use App\Entity\OverallGameStats;
use App\Entity\Purchase;
use App\Repository\OverallGameStatsRepository;
use App\Repository\PurchaseRepository;
use App\Service\Stats\WastedMoneyService;
use App\Service\Util\PurchaseUtil;
use PHPUnit\Framework\TestCase;

/**
 * Class WastedMoneyServiceTest
 */
class WastedMoneyServiceTest extends TestCase
{
    public function testRecalculate(): void
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

        $game = new Game();
        $game->setTimePlayed(20);

        $purchase = new Purchase();
        $purchase->setCurrency(getenv('DEFAULT_CURRENCY'));
        $purchase->setPrice(100);
        $purchase->setGame($game);

        $purchaseRepositoryMock = $this->createMock(PurchaseRepository::class);
        $purchaseRepositoryMock->expects($this->any())
            ->method('findAll')
            ->willReturn([$purchase]);

        $wastedMoneyService = new WastedMoneyService(
            $purchaseUtilMock,
            $overallGameStatsRepositoryMock,
            $purchaseRepositoryMock
        );

        $this->assertEquals(
            $expectedOverallGameStats,
            $wastedMoneyService->recalculate()
        );
    }
}
