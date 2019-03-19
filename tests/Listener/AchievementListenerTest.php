<?php

namespace App\Tests\Listener;

use App\Entity\Achievement;
use App\Entity\Game;
use App\Entity\OverallGameStats;
use App\Entity\User;
use App\Listener\GameSessionListener;
use App\Repository\OverallGameStatsRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
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

        $listener = new GameSessionListener();
        $listener->postPersist($argsMock);
    }

    public function testPostPersistShouldGetTheGameStatsRepository(): void
    {
        $argsMock = $this->createMock(LifecycleEventArgs::class);
        $argsMock->expects($this->once())
            ->method('getEntity')
            ->willReturn(new Achievement(new User(1), new Game(1)));

        $overallGameStatsRepositoryMock = $this->createMock(OverallGameStatsRepository::class);

        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->expects($this->any())
            ->method('getRepository')
            ->with(OverallGameStats::class)
            ->willReturn($overallGameStatsRepositoryMock);

        $argsMock->expects($this->any())
            ->method('getEntityManager')
            ->willReturn($entityManagerMock);

        $listener = new GameSessionListener();
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

        $listener = new GameSessionListener();
        $listener->postUpdate($argsMock);
    }
}
