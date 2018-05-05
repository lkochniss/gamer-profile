<?php

namespace tests\App\Command\Steam;

use App\Entity\GameSession;
use App\Entity\OverallGameStats;
use App\Entity\Purchase;
use App\Listener\InvestedMoneyPurchaseListener;
use App\Repository\OverallGameStatsRepository;
use App\Service\Util\PurchaseUtil;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\UnitOfWork;
use PHPUnit\Framework\TestCase;

/**
 * Class InvestedMoneyPurchaseListenerTest
 */
class InvestedMoneyPurchaseListenerTest extends TestCase
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
        $investedMoneyPurchaseListener = new InvestedMoneyPurchaseListener($purchaseUtilMock);

        $this->assertEquals('S', $investedMoneyPurchaseListener->postPersist($argsMock));
    }

    public function testPostPersistWorksCorrect(): void
    {
        $purchase = new Purchase();
        $purchase->setPrice(10);
        $purchase->setType(Purchase::OTHER_PURCHASE);
        $purchase->setCurrency(getenv('DEFAULT_CURRENCY'));

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

        $investedMoneyPurchaseListener = new InvestedMoneyPurchaseListener($purchaseUtilMock);

        $this->assertEquals('U', $investedMoneyPurchaseListener->postPersist($argsMock));
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
        $investedMoneyPurchaseListener = new InvestedMoneyPurchaseListener($purchaseUtilMock);

        $this->assertEquals('S', $investedMoneyPurchaseListener->postUpdate($argsMock));
    }

    /**
     * @param array $changeSet
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @dataProvider changeSetProvider
     */
    public function testPostUpdateWorksCorrectWithGamePurchase(array $changeSet): void
    {
        $purchase = new Purchase();
        $purchase->setPrice(10);
        $purchase->setCurrency(getenv('DEFAULT_CURRENCY'));

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
        $investedMoneyPurchaseListener = new InvestedMoneyPurchaseListener($purchaseUtilMock);

        $this->assertEquals('U', $investedMoneyPurchaseListener->postUpdate($argsMock));
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
        ];
    }
}
