<?php

namespace App\Tests\Listener;

use App\Entity\Achievement;
use App\Entity\Game;
use App\Entity\OverallGameStats;
use App\Listener\AchievementListener;
use App\Repository\OverallGameStatsRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\UnitOfWork;
use PHPUnit\Framework\TestCase;

/**
 * Class AchievementListenerTest
 */
class AchievementListenerTest extends TestCase
{
    public function testPostPersistShouldSkipsOnWrongEntity(): void
    {
        $argsMock = $this->createMock(LifecycleEventArgs::class);
        $argsMock->expects($this->once())
            ->method('getEntity')
            ->willReturn(new Game(1));

        $argsMock->expects($this->never())
            ->method('getEntityManager');

        $listener = new AchievementListener();
        $listener->postPersist($argsMock);
    }

    public function testPostPersistShouldGetTheGameStatsRepository(): void
    {
        $argsMock = $this->createMock(LifecycleEventArgs::class);
        $argsMock->expects($this->once())
            ->method('getEntity')
            ->willReturn(new Achievement(1, new Game(1)));

        $overallGameStatsRepositoryMock = $this->createMock(OverallGameStatsRepository::class);

        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->expects($this->any())
            ->method('getRepository')
            ->with(OverallGameStats::class)
            ->willReturn($overallGameStatsRepositoryMock);

        $argsMock->expects($this->any())
            ->method('getEntityManager')
            ->willReturn($entityManagerMock);

        $listener = new AchievementListener();
        $listener->postPersist($argsMock);
    }

    public function testPostUpdateShouldSkipsOnWrongEntity(): void
    {
        $argsMock = $this->createMock(LifecycleEventArgs::class);
        $argsMock->expects($this->once())
            ->method('getEntity')
            ->willReturn(new Game(1));

        $argsMock->expects($this->never())
            ->method('getEntityManager');

        $listener = new AchievementListener();
        $listener->postUpdate($argsMock);
    }

    public function testPostUpdateShouldGetTheOverallGameStatsRepository(): void
    {
        $entity = new Achievement(1, new Game(1));

        $argsMock = $this->createMock(LifecycleEventArgs::class);
        $argsMock->expects($this->once())
            ->method('getEntity')
            ->willReturn($entity);

        $overallGameStatsRepositoryMock = $this->createMock(OverallGameStatsRepository::class);

        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->expects($this->any())
            ->method('getRepository')
            ->with(OverallGameStats::class)
            ->willReturn($overallGameStatsRepositoryMock);

        $unitOfWorkMock = $this->createMock(UnitOfWork::class);
        $unitOfWorkMock->expects($this->once())
            ->method('getEntityChangeSet')
            ->with($entity)
            ->willReturn([]);

        $entityManagerMock->expects($this->once())
            ->method('getUnitOfWork')
            ->willReturn($unitOfWorkMock);

        $argsMock->expects($this->any())
            ->method('getEntityManager')
            ->willReturn($entityManagerMock);

        $listener = new AchievementListener();
        $listener->postUpdate($argsMock);
    }

    public function testPostUpdateShouldSaveTheChangeSet(): void
    {
        $steamUserId = 1;
        $entity = new Achievement($steamUserId, new Game(1));

        $argsMock = $this->createMock(LifecycleEventArgs::class);
        $argsMock->expects($this->once())
            ->method('getEntity')
            ->willReturn($entity);

        $overallGameStatsRepositoryMock = $this->createMock(OverallGameStatsRepository::class);

        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->expects($this->any())
            ->method('getRepository')
            ->with(OverallGameStats::class)
            ->willReturn($overallGameStatsRepositoryMock);

        $unitOfWorkMock = $this->createMock(UnitOfWork::class);
        $unitOfWorkMock->expects($this->once())
            ->method('getEntityChangeSet')
            ->with($entity)
            ->willReturn([
                'overallAchievements' => [
                    1,
                    1
                ],
                'playerAchievements' => [
                    0,
                    1
                ]
            ]);

        $entityManagerMock->expects($this->once())
            ->method('getUnitOfWork')
            ->willReturn($unitOfWorkMock);

        $argsMock->expects($this->any())
            ->method('getEntityManager')
            ->willReturn($entityManagerMock);

        $expectedStats = new OverallGameStats($steamUserId);
        $expectedStats->addToOverallAchievements(1);
        $expectedStats->addToPlayerAchievements(1);

        $overallGameStats = new OverallGameStats(1);
        $overallGameStats->addToOverallAchievements(1);
        $overallGameStatsRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with([
                'steamUserId' => $steamUserId
            ])
            ->willReturn($overallGameStats);

        $overallGameStatsRepositoryMock->expects($this->once())
            ->method('save')
            ->with($expectedStats);

        $listener = new AchievementListener();
        $listener->postUpdate($argsMock);
    }
}
