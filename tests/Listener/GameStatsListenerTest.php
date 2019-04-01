<?php

namespace App\Tests\Listener;

use App\Entity\Achievement;
use App\Entity\Game;
use App\Entity\GameStats;
use App\Entity\OverallGameStats;
use App\Entity\Playtime;
use App\Entity\User;
use App\Listener\GameStatsListener;
use App\Repository\OverallGameStatsRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\UnitOfWork;
use PHPUnit\Framework\TestCase;

/**
 * Class GameStatsListenerTest
 */
class GameStatsListenerTest extends TestCase
{
    public function testPostPersistShouldSkipsOnWrongEntity(): void
    {
        $argsMock = $this->createMock(LifecycleEventArgs::class);
        $argsMock->expects($this->once())
            ->method('getEntity')
            ->willReturn(new Game(1));

        $argsMock->expects($this->never())
            ->method('getEntityManager');

        $listener = new GameStatsListener();
        $listener->postPersist($argsMock);
    }

    public function testPostPersistShouldGetTheGameStatsRepository(): void
    {
        $steamUserId = 1;
        $game = new Game(1);
        $gameStats = new GameStats(
            $steamUserId,
            $game,
            new Achievement($steamUserId, $game),
            new Playtime($steamUserId, $game)
        );
        $argsMock = $this->createMock(LifecycleEventArgs::class);
        $argsMock->expects($this->once())
            ->method('getEntity')
            ->willReturn($gameStats);

        $repositoryMock = $this->createMock(OverallGameStatsRepository::class);
        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->expects($this->once())
            ->method('getRepository')
            ->with(OverallGameStats::class)
            ->willReturn($repositoryMock);

        $argsMock->expects($this->any())
            ->method('getEntityManager')
            ->willReturn($entityManagerMock);

        $listener = new GameStatsListener();
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

        $listener = new GameStatsListener();
        $listener->postUpdate($argsMock);
    }
}
