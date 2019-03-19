<?php

namespace App\Tests\Listener;

use App\Entity\Game;
use App\Entity\GameSession;
use App\Entity\GameSessionsPerMonth;
use App\Entity\User;
use App\Listener\GameSessionListener;
use App\Repository\GameSessionsPerMonthRepository;
use App\Repository\OverallGameStatsRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use PHPUnit\Framework\TestCase;

/**
 * Class GameSessionListenerTest
 */
class GameSessionListenerTest extends TestCase
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
            ->willReturn(new GameSession(new Game(1), new User(1)));

        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->expects($this->any())
            ->method('getRepository')
            ->will($this->returnCallback(function ($args) {
                if ($args === GameSessionsPerMonth::class) {
                    return $this->createMock(GameSessionsPerMonthRepository::class);
                }

                return $this->createMock(OverallGameStatsRepository::class);
            }));

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
