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
use Doctrine\ORM\UnitOfWork;
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
        $entityManagerMock->expects($this->exactly(2))
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

    public function testPostUpdateShouldGetTheGameStatsRepository(): void
    {
        $gameSession = new GameSession(new Game(1), new User(1));
        $argsMock = $this->createMock(LifecycleEventArgs::class);
        $argsMock->expects($this->once())
            ->method('getEntity')
            ->willReturn($gameSession);

        $repositoryMock = $this->createMock(GameSessionsPerMonthRepository::class);

        $entityManagerMock = $this->createMock(EntityManager::class);

        $unitOfWorkMock = $this->createMock(UnitOfWork::class);
        $unitOfWorkMock->expects($this->once())
            ->method('getEntityChangeSet')
            ->with($gameSession)
            ->willReturn([]);

        $entityManagerMock->expects($this->once())
            ->method('getUnitOfWork')
            ->willReturn($unitOfWorkMock);

        $entityManagerMock->expects($this->once())
            ->method('getRepository')
            ->with(GameSessionsPerMonth::class)
            ->willReturn($repositoryMock);


        $argsMock->expects($this->any())
            ->method('getEntityManager')
            ->willReturn($entityManagerMock);

        $listener = new GameSessionListener();
        $listener->postUpdate($argsMock);
    }
}
