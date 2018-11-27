<?php

namespace tests\App\Command\Steam;

use App\Entity\Game;
use App\Entity\GameSession;
use App\Entity\GameSessionsPerMonth;
use App\Entity\OverallGameStats;
use App\Listener\GameSessionsPerMonthListener;
use App\Repository\GameSessionsPerMonthRepository;
use App\Repository\OverallGameStatsRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\UnitOfWork;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class GameSessionsPerMonthListenerTest
 */
class GameSessionsPerMonthListenerTest extends TestCase
{
    /**
     * @var MockObject
     */
    private $overallGameStatsRepositoryMock;

    /**
     * @var MockObject
     */
    private $gameSessionPerMonthRepositoryMock;

    public function setUp()
    {
        $this->overallGameStatsRepositoryMock = $this->createMock(OverallGameStatsRepository::class);
        $this->gameSessionPerMonthRepositoryMock = $this->createMock(GameSessionsPerMonthRepository::class);
    }

    public function testPostPersistSkipsOnWrongEntity(): void
    {
        $argsMock = $this->createMock(LifecycleEventArgs::class);
        $argsMock->expects($this->any())
            ->method('getEntity')
            ->willReturn(new Game());

        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->expects($this->any())
            ->method('getRepository')
            ->with($this->logicalOr(
                OverallGameStats::class,
                GameSessionsPerMonth::class
            ))
            ->will($this->returnCallback([$this, 'getEntityManagerCallback']));

        $argsMock->expects($this->any())
            ->method('getEntityManager')
            ->willReturn($entityManagerMock);


        $gameSessionsPerMonthListener = new GameSessionsPerMonthListener();

        $this->assertEquals('S', $gameSessionsPerMonthListener->postPersist($argsMock));
    }

    public function testPostPersistWorksCorrect(): void
    {
        $gameSession = new GameSession(new Game());
        $gameSession->setDuration(10);

        $argsMock = $this->createMock(LifecycleEventArgs::class);
        $argsMock->expects($this->any())
            ->method('getEntity')
            ->willReturn($gameSession);

        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->expects($this->any())
            ->method('getRepository')
            ->with($this->logicalOr(
                OverallGameStats::class,
                GameSessionsPerMonth::class
            ))
            ->will($this->returnCallback([$this, 'getEntityManagerCallback']));

        $argsMock->expects($this->any())
            ->method('getEntityManager')
            ->willReturn($entityManagerMock);


        $gameSessionsPerMonthListener = new GameSessionsPerMonthListener();

        $this->assertEquals('U', $gameSessionsPerMonthListener->postPersist($argsMock));
    }

    public function testPostUpdateSkipsOnWrongEntity(): void
    {
        $argsMock = $this->createMock(LifecycleEventArgs::class);
        $argsMock->expects($this->any())
            ->method('getEntity')
            ->willReturn(new Game());

        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->expects($this->any())
            ->method('getRepository')
            ->with($this->logicalOr(
                OverallGameStats::class,
                GameSessionsPerMonth::class
            ))
            ->will($this->returnCallback([$this, 'getEntityManagerCallback']));

        $argsMock->expects($this->any())
            ->method('getEntityManager')
            ->willReturn($entityManagerMock);


        $gameSessionsPerMonthListener = new GameSessionsPerMonthListener();

        $this->assertEquals('S', $gameSessionsPerMonthListener->postUpdate($argsMock));
    }

    /**
     * @param array $changeSet
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @dataProvider changeSetProvider
     */
    public function testPostUpdateWorksCorrect(array $changeSet): void
    {
        $gameSession = new GameSession(new Game());
        $gameSession->setDuration(10);

        $argsMock = $this->createMock(LifecycleEventArgs::class);
        $argsMock->expects($this->any())
            ->method('getEntity')
            ->willReturn($gameSession);

        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->expects($this->any())
            ->method('getRepository')
            ->with($this->logicalOr(
                OverallGameStats::class,
                GameSessionsPerMonth::class
            ))
            ->will($this->returnCallback([$this, 'getEntityManagerCallback']));

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


        $gameSessionsPerMonthListener = new GameSessionsPerMonthListener();

        $this->assertEquals('U', $gameSessionsPerMonthListener->postPersist($argsMock));
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

    /**
     * @param string $entityManagerClass
     * @return MockObject
     */
    public function getEntityManagerCallback(string $entityManagerClass): MockObject
    {
        if ($entityManagerClass === OverallGameStats::class) {
            return $this->overallGameStatsRepositoryMock;
        }

        return $this->gameSessionPerMonthRepositoryMock;
    }
}
