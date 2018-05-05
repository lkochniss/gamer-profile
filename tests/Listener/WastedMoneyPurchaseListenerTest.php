<?php

namespace tests\App\Command\Steam;

use App\Entity\Game;
use App\Entity\GameSession;
use App\Entity\OverallGameStats;
use App\Entity\Purchase;
use App\Listener\WastedMoneyPurchaseListener;
use App\Repository\OverallGameStatsRepository;
use App\Service\Util\PurchaseUtil;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\UnitOfWork;
use PHPUnit\Framework\TestCase;

/**
 * Class WastedMoneyPurchaseListenerTest
 */
class WastedMoneyPurchaseListenerTest extends TestCase
{
    public function testPostPersistSkipsOnWrongEntity(): void
    {
        $argsMock = $this->createMock(LifecycleEventArgs::class);
        $argsMock->expects($this->any())
            ->method('getEntity')
            ->willReturn(new GameSession());

        $overallGameStatsRepositoryMock = $this->createMock(OverallGameStatsRepository::class);

        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->expects($this->any())
            ->method('getRepository')
            ->with(OverallGameStats::class)
            ->willReturn($overallGameStatsRepositoryMock);

        $argsMock->expects($this->any())
            ->method('getEntityManager')
            ->willReturn($entityManagerMock);

        $purchaseUtilMock = $this->createMock(PurchaseUtil::class);
        $wastedMoneyPurchaseListener = new WastedMoneyPurchaseListener($purchaseUtilMock);

        $this->assertEquals('S', $wastedMoneyPurchaseListener->postPersist($argsMock));
    }

    public function testPostPersistWorksCorrect(): void
    {
        $game = new Game();
        $game->setTimePlayed(10);

        $purchase = new Purchase();
        $purchase->setPrice(10);
        $purchase->setType(Purchase::OTHER_PURCHASE);
        $purchase->setCurrency(getenv('DEFAULT_CURRENCY'));
        $purchase->setGame($game);

        $argsMock = $this->createMock(LifecycleEventArgs::class);
        $argsMock->expects($this->any())
            ->method('getEntity')
            ->willReturn($purchase);

        $overallGameStatsRepositoryMock = $this->createMock(OverallGameStatsRepository::class);

        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->expects($this->any())
            ->method('getRepository')
            ->with(OverallGameStats::class)
            ->willReturn($overallGameStatsRepositoryMock);

        $argsMock->expects($this->any())
            ->method('getEntityManager')
            ->willReturn($entityManagerMock);

        $purchaseUtilMock = $this->createMock(PurchaseUtil::class);

        $wastedMoneyPurchaseListener = new WastedMoneyPurchaseListener($purchaseUtilMock);

        $this->assertEquals('U', $wastedMoneyPurchaseListener->postPersist($argsMock));
    }

    public function testPostUpdateSkipsOnWrongEntity(): void
    {
        $argsMock = $this->createMock(LifecycleEventArgs::class);
        $argsMock->expects($this->any())
            ->method('getEntity')
            ->willReturn(new GameSession());

        $overallGameStatsRepositoryMock = $this->createMock(OverallGameStatsRepository::class);

        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->expects($this->any())
            ->method('getRepository')
            ->with(OverallGameStats::class)
            ->willReturn($overallGameStatsRepositoryMock);

        $argsMock->expects($this->any())
            ->method('getEntityManager')
            ->willReturn($entityManagerMock);

        $purchaseUtilMock = $this->createMock(PurchaseUtil::class);
        $wastedMoneyPurchaseListener = new WastedMoneyPurchaseListener($purchaseUtilMock);

        $this->assertEquals('S', $wastedMoneyPurchaseListener->postUpdate($argsMock));
    }

    /**
     * @param array $changeSet
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @dataProvider changeSetProvider
     */
    public function testPostUpdateWorksCorrect(array $changeSet): void
    {
        $game = new Game();
        $game->setTimePlayed(1);
        $purchase = new Purchase();
        $purchase->setPrice(10);
        $purchase->setCurrency(getenv('DEFAULT_CURRENCY'));
        $purchase->setGame($game);

        $argsMock = $this->createMock(LifecycleEventArgs::class);
        $argsMock->expects($this->any())
            ->method('getEntity')
            ->willReturn($purchase);

        $overallGameStatsRepositoryMock = $this->createMock(OverallGameStatsRepository::class);

        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->expects($this->any())
            ->method('getRepository')
            ->with(OverallGameStats::class)
            ->willReturn($overallGameStatsRepositoryMock);

        $unitOfWorkMock = $this->createMock(UnitOfWork::class);
        $unitOfWorkMock->expects($this->any())
            ->method('getEntityChangeSet')
            ->willReturn($changeSet);

        $entityManagerMock->expects($this->any())
            ->method('getUnitOfWork')
            ->willReturn($unitOfWorkMock);

        $argsMock->expects($this->any())
            ->method('getEntityManager')
            ->willReturn($entityManagerMock);

        $purchaseUtilMock = $this->createMock(PurchaseUtil::class);
        $wastedMoneyPurchaseListener = new WastedMoneyPurchaseListener($purchaseUtilMock);

        $this->assertEquals('U', $wastedMoneyPurchaseListener->postUpdate($argsMock));
    }

    /**
     * @return array
     */
    public function changeSetProvider(): array
    {
        return [
            [
                []
            ],
            [
                [
                    'price' => [0, 1]
                ]
            ],
            [
                [
                    'timePlayed' => [0, 1]
                ]
            ],
        ];
    }
}
