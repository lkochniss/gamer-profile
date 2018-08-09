<?php

namespace tests\App\Service\Stats;

use App\Entity\Achievement;
use App\Entity\Game;
use App\Entity\GameStats;
use App\Entity\OverallGameStats;
use App\Entity\Playtime;
use App\Entity\Purchase;
use App\Entity\User;
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
        $user = new User(1);
        $expectedOverallGameStats = new OverallGameStats($user);
        $expectedOverallGameStats->addToWastedMoney(100);

        $overallGameStatsRepositoryMock = $this->createMock(OverallGameStatsRepository::class);
        $overallGameStatsRepositoryMock->expects($this->any())
            ->method('findOneBy')
            ->with(['user' => $user])
            ->willReturn(new OverallGameStats($user));

        $overallGameStatsRepositoryMock->expects($this->any())
            ->method('save')
            ->with($expectedOverallGameStats);

        $game = new Game();

        $gameStats = new GameStats($user, $game, new Achievement($user, $game), new Playtime($user, $game));

        $purchase = new Purchase($user);
        $purchase->setCurrency(getenv('DEFAULT_CURRENCY'));
        $purchase->setPrice(100);
        $purchase->setGame($game);
        $purchase->setGameStats($gameStats);

        $purchaseRepositoryMock = $this->createMock(PurchaseRepository::class);
        $purchaseRepositoryMock->expects($this->any())
            ->method('findBy')
            ->with(['user' => $user])
            ->willReturn([$purchase]);

        $wastedMoneyService = new WastedMoneyService(
            $overallGameStatsRepositoryMock,
            $purchaseRepositoryMock
        );

        $this->assertEquals(
            $expectedOverallGameStats,
            $wastedMoneyService->recalculate($user)
        );
    }
}
