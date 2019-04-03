<?php

namespace App\Tests\Listener;

use App\Entity\Game;
use App\Entity\GameSession;
use App\Entity\PlaytimePerMonth;
use App\Listener\PlaytimePerMonthListener;
use App\Repository\PlaytimePerMonthRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use PHPUnit\Framework\TestCase;

/**
 * Class PlaytimePerMonthListenerTest
 */
class PlaytimePerMonthListenerTest extends TestCase
{
    public function testPostPersistShouldSkipsOnWrongEntity(): void
    {
        $argsMock = $this->createMock(LifecycleEventArgs::class);
        $argsMock->expects($this->once())
            ->method('getEntity')
            ->willReturn(new Game(1));

        $argsMock->expects($this->never())
            ->method('getEntityManager');

        $listener = new PlaytimePerMonthListener();
        $listener->postPersist($argsMock);
    }

    public function testPostPersistShouldGetTheGameStatsRepository(): void
    {
        $steamUserId = 1;
        $game = new Game(1);
        $gameSession = new GameSession(
            $game,
            $steamUserId
        );
        $argsMock = $this->createMock(LifecycleEventArgs::class);
        $argsMock->expects($this->once())
            ->method('getEntity')
            ->willReturn($gameSession);

        $repositoryMock = $this->createMock(PlaytimePerMonthRepository::class);
        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->expects($this->once())
            ->method('getRepository')
            ->with(PlaytimePerMonth::class)
            ->willReturn($repositoryMock);

        $argsMock->expects($this->any())
            ->method('getEntityManager')
            ->willReturn($entityManagerMock);

        $listener = new PlaytimePerMonthListener();
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

        $listener = new PlaytimePerMonthListener();
        $listener->postUpdate($argsMock);
    }
}
