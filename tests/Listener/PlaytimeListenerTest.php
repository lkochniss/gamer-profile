<?php

namespace App\Tests\Listener;

use App\Entity\Game;
use App\Entity\OverallGameStats;
use App\Entity\Playtime;
use App\Entity\PlaytimePerMonth;
use App\Listener\PlaytimeListener;
use App\Repository\OverallGameStatsRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\UnitOfWork;
use PHPUnit\Framework\TestCase;

/**
 * Class PlaytimeListenerTest
 */
class PlaytimeListenerTest extends TestCase
{
    public function testPostPersistShouldSkipsOnWrongEntity(): void
    {
        $argsMock = $this->createMock(LifecycleEventArgs::class);
        $argsMock->expects($this->once())
            ->method('getEntity')
            ->willReturn(new Game(1));

        $argsMock->expects($this->never())
            ->method('getEntityManager');

        $listener = new PlaytimeListener();
        $listener->postPersist($argsMock);
    }

    public function testPostPersistShouldGetTheOverallGameStatsRepository(): void
    {
        $steamUserId = 1;
        $game = new Game(1);
        $playtime = new Playtime(
            $steamUserId,
            $game
        );
        $argsMock = $this->createMock(LifecycleEventArgs::class);
        $argsMock->expects($this->once())
            ->method('getEntity')
            ->willReturn($playtime);

        $repositoryMock = $this->createMock(OverallGameStatsRepository::class);
        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->expects($this->once())
            ->method('getRepository')
            ->with(OverallGameStats::class)
            ->willReturn($repositoryMock);

        $argsMock->expects($this->any())
            ->method('getEntityManager')
            ->willReturn($entityManagerMock);

        $listener = new PlaytimeListener();
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

        $listener = new PlaytimeListener();
        $listener->postUpdate($argsMock);
    }

    public function testPostUpdateShouldSaveTheChangeSet(): void
    {
        $steamUserId = 1;
        $entity = new Playtime($steamUserId, new Game(1));

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
                'overallPlaytime' => [
                    1,
                    1
                ],
                'recentPlaytime' => [
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
        $expectedStats->addToOverallPlaytime(1);
        $expectedStats->addToRecentPlaytime(1);

        $overallGameStats = new OverallGameStats(1);
        $overallGameStats->addToOverallPlaytime(1);
        $overallGameStatsRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with([
                'steamUserId' => $steamUserId
            ])
            ->willReturn($overallGameStats);

        $overallGameStatsRepositoryMock->expects($this->once())
            ->method('save')
            ->with($expectedStats);

        $listener = new PlaytimeListener();
        $listener->postUpdate($argsMock);
    }
}
