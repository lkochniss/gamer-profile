<?php

namespace tests\App\Command\Steam;

use App\Entity\Game;
use App\Entity\GameSession;
use App\Entity\OverallGameStats;
use App\Listener\AchievemenListener;
use App\Repository\OverallGameStatsRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\UnitOfWork;
use PHPUnit\Framework\TestCase;

/**
 * Class BasicInformationListenerTest
 */
class BasicInformationListenerTest extends TestCase
{
    public function testPostPersistSkipsOnWrongEntity(): void
    {
        $argsMock = $this->createMock(LifecycleEventArgs::class);
        $argsMock->expects($this->any())
            ->method('getEntity')
            ->willReturn(new GameSession(new Game(1)));

        $overallGameStatsRepositoryMock = $this->createMock(OverallGameStatsRepository::class);

        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->expects($this->any())
            ->method('getRepository')
            ->with(OverallGameStats::class)
            ->willReturn($overallGameStatsRepositoryMock);

        $argsMock->expects($this->any())
            ->method('getEntityManager')
            ->willReturn($entityManagerMock);

        $basicInformationListener = new AchievemenListener();

        $this->assertEquals('S', $basicInformationListener->postPersist($argsMock));
    }

    public function testPostPersistWorksCorrect(): void
    {
        $game = new Game(1);
        $game->setRecentlyPlayed(10);
        $game->setTimePlayed(10);

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

        $basicInformationListener = new AchievemenListener();

        $this->assertEquals('U', $basicInformationListener->postPersist($argsMock));
    }

    public function testPostUpdateSkipsOnWrongEntity(): void
    {
        $argsMock = $this->createMock(LifecycleEventArgs::class);
        $argsMock->expects($this->any())
            ->method('getEntity')
            ->willReturn(new GameSession(new Game(1)));

        $overallGameStatsRepositoryMock = $this->createMock(OverallGameStatsRepository::class);

        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->expects($this->any())
            ->method('getRepository')
            ->with(OverallGameStats::class)
            ->willReturn($overallGameStatsRepositoryMock);

        $argsMock->expects($this->any())
            ->method('getEntityManager')
            ->willReturn($entityManagerMock);

        $basicInformationListener = new AchievemenListener();

        $this->assertEquals('S', $basicInformationListener->postUpdate($argsMock));
    }

    /**
     * @param array $changeSet
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @dataProvider changeSetProvider
     */
    public function testPostUpdateWorksCorrect(array $changeSet): void
    {
        $game = new Game(1);
        $game->setRecentlyPlayed(10);
        $game->setTimePlayed(10);

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
            ->willReturn($changeSet);

        $entityManagerMock->expects($this->any())
            ->method('getUnitOfWork')
            ->willReturn($unitOfWorkMock);

        $argsMock->expects($this->any())
            ->method('getEntityManager')
            ->willReturn($entityManagerMock);

        $basicInformationListener = new AchievemenListener();

        $this->assertEquals('U', $basicInformationListener->postUpdate($argsMock));
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
                    'overallAchievements' => [0, 1]
                ]
            ],
            [
                [
                    'playerAchievements' => [0, 1]
                ]
            ],
            [
                [
                    'recentlyPlayed' => [0, 1]
                ]
            ],
            [
                [
                    'timePlayed' => [0, 1]
                ]
            ],
        ];
    }
}
