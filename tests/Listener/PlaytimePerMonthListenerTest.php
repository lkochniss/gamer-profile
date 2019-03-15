<?php

namespace tests\App\Command\Steam;

use App\Entity\Game;
use App\Entity\GameSession;
use App\Entity\PlaytimePerMonth;
use App\Listener\PlaytimePerMonthListener;
use App\Repository\PlaytimePerMonthRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\UnitOfWork;
use PHPUnit\Framework\TestCase;

/**
 * Class PlaytimePerMonthListenerTest
 */
class PlaytimePerMonthListenerTest extends TestCase
{
    public function testPostPersistAndPostUpdateSkipOnWrongEntity(): void
    {
        $argsMock = $this->createMock(LifecycleEventArgs::class);
        $argsMock->expects($this->any())
            ->method('getEntity')
            ->willReturn(new Game(1));

        $repositoryMock = $this->createMock(PlaytimePerMonthRepository::class);

        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->expects($this->any())
            ->method('getRepository')
            ->with(PlaytimePerMonth::class)
            ->willReturn($repositoryMock);

        $argsMock->expects($this->any())
            ->method('getEntityManager')
            ->willReturn($entityManagerMock);

        $playtimePerMonthListener = new PlaytimePerMonthListener();

        $this->assertEquals('S', $playtimePerMonthListener->postPersist($argsMock));
        $this->assertEquals('S', $playtimePerMonthListener->postUpdate($argsMock));
    }

    public function testPostPersistWorksCorrect(): void
    {
        $gameSession = new GameSession(new Game(1));
        $gameSession->setDuration(10);

        $argsMock = $this->createMock(LifecycleEventArgs::class);
        $argsMock->expects($this->any())
            ->method('getEntity')
            ->willReturn($gameSession);

        $repositoryMock = $this->createMock(PlaytimePerMonthRepository::class);

        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->expects($this->any())
            ->method('getRepository')
            ->with(PlaytimePerMonth::class)
            ->willReturn($repositoryMock);

        $argsMock->expects($this->any())
            ->method('getEntityManager')
            ->willReturn($entityManagerMock);

        $playtimePerMonthListener = new PlaytimePerMonthListener();

        $this->assertEquals('U', $playtimePerMonthListener->postPersist($argsMock));
    }

    /**
     * @param array $changeSet
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @dataProvider changeSetProvider
     */
    public function testPostUpdateWorksCorrect(array $changeSet): void
    {
        $gameSession = new GameSession(new Game(1));
        $gameSession->setDuration(10);

        $argsMock = $this->createMock(LifecycleEventArgs::class);
        $argsMock->expects($this->any())
            ->method('getEntity')
            ->willReturn($gameSession);

        $repositoryMock = $this->createMock(PlaytimePerMonthRepository::class);

        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->expects($this->any())
            ->method('getRepository')
            ->with(PlaytimePerMonth::class)
            ->willReturn($repositoryMock);

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

        $playtimePerMonthListener = new PlaytimePerMonthListener();

        $this->assertEquals('U', $playtimePerMonthListener->postUpdate($argsMock));
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
                    'duration' => [0, 1]
                ]
            ],
        ];
    }
}
