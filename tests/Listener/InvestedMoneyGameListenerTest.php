<?php

namespace tests\App\Command\Steam;

use App\Entity\Game;
use App\Entity\GameSession;
use App\Entity\OverallGameStats;
use App\Listener\InvestedMoneyGameListener;
use App\Repository\OverallGameStatsRepository;
use App\Service\Util\PurchaseUtil;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\UnitOfWork;
use PHPUnit\Framework\TestCase;

/**
 * Class InvestedMoneyGameListenerTest
 */
class InvestedMoneyGameListenerTest extends TestCase
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
        $investedMoneyGameListener = new InvestedMoneyGameListener($purchaseUtilMock);

        $this->assertEquals('S', $investedMoneyGameListener->postPersist($argsMock));
    }

    public function testPostPersistWorksCorrect(): void
    {
        $game = new Game();

        $argsMock = $this->createMock(LifecycleEventArgs::class);
        $argsMock->expects($this->any())
            ->method('getEntity')
            ->willReturn($game);

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
        $investedMoneyGameListener = new InvestedMoneyGameListener($purchaseUtilMock);

        $this->assertEquals('U', $investedMoneyGameListener->postPersist($argsMock));
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
        $investedMoneyGameListener = new InvestedMoneyGameListener($purchaseUtilMock);

        $this->assertEquals('S', $investedMoneyGameListener->postUpdate($argsMock));
    }

    public function testPostUpdateWorksCorrect(): void
    {
        $game = new Game();

        $argsMock = $this->createMock(LifecycleEventArgs::class);
        $argsMock->expects($this->any())
            ->method('getEntity')
            ->willReturn($game);

        $overallGameStatsRepositoryMock = $this->createMock(OverallGameStatsRepository::class);

        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->expects($this->any())
            ->method('getRepository')
            ->with(OverallGameStats::class)
            ->willReturn($overallGameStatsRepositoryMock);

        $unitOfWorkMock = $this->createMock(UnitOfWork::class);
        $unitOfWorkMock->expects($this->any())
            ->method('getEntityChangeSet')
            ->willReturn([]);

        $entityManagerMock->expects($this->any())
            ->method('getUnitOfWork')
            ->willReturn($unitOfWorkMock);

        $argsMock->expects($this->any())
            ->method('getEntityManager')
            ->willReturn($entityManagerMock);

        $purchaseUtilMock = $this->createMock(PurchaseUtil::class);
        $investedMoneyGameListener = new InvestedMoneyGameListener($purchaseUtilMock);

        $this->assertEquals('U', $investedMoneyGameListener->postUpdate($argsMock));
    }
}
