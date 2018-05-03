<?php

namespace tests\App\Service\Stats;

use App\Entity\Game;
use App\Entity\GameSession;
use App\Entity\GameSessionsPerMonth;
use App\Entity\OverallGameStats;
use App\Repository\GameSessionsPerMonthRepository;
use App\Repository\OverallGameStatsRepository;
use App\Service\Stats\GameSessionsPerMonthService;
use PHPUnit\Framework\TestCase;

/**
 * Class GameSessionsPerMonthServiceTest
 */
class GameSessionsPerMonthServiceTest extends TestCase
{
    public function testAddSessionToOverallGameStats(): void
    {
        $expectedOverallGameStats = new OverallGameStats();
        $expectedOverallGameStats->addGameSessions();

        $overallGameStatsRepositoryMock = $this->createMock(OverallGameStatsRepository::class);
        $overallGameStatsRepositoryMock->expects($this->any())
            ->method('findOneBy')
            ->with(['identifier' => getenv('STEAM_USER_ID')])
            ->willReturn(new OverallGameStats());

        $overallGameStatsRepositoryMock->expects($this->any())
            ->method('save')
            ->with($expectedOverallGameStats);

        $repositoryMock = $this->createMock(GameSessionsPerMonthRepository::class);

        $basicInformationService = new GameSessionsPerMonthService(
            $repositoryMock,
            $overallGameStatsRepositoryMock
        );

        $this->assertEquals(
            $expectedOverallGameStats,
            $basicInformationService->addSessionToOverallGameStats()
        );
    }

    public function testAddGameSession(): void
    {
        $game = new Game();
        $gameSession = new GameSession();
        $gameSession->setGame($game);
        $gameSession->setDuration(10);

        $month = new \DateTime('first day of this month 00:00:00');

        $expectedGameSessionsPerMonth = new GameSessionsPerMonth($month, $game);
        $expectedGameSessionsPerMonth->addToDuration(10);

        $overallGameStatsRepositoryMock = $this->createMock(OverallGameStatsRepository::class);
        $repositoryMock = $this->createMock(GameSessionsPerMonthRepository::class);
        $repositoryMock->expects($this->any())
            ->method('findOneBy')
            ->with(['game' => $game, 'month' => $month])
            ->willReturn(new GameSessionsPerMonth($month, $game));

        $repositoryMock->expects($this->any())
            ->method('save')
            ->with($expectedGameSessionsPerMonth);

        $basicInformationService = new GameSessionsPerMonthService(
            $repositoryMock,
            $overallGameStatsRepositoryMock
        );

        $this->assertEquals(
            $expectedGameSessionsPerMonth,
            $basicInformationService->addGameSession($gameSession)
        );
    }

    public function testUpdateGameSession(): void
    {
        $game = new Game();
        $gameSession = new GameSession();
        $gameSession->setGame($game);
        $gameSession->setDuration(20);

        $month = new \DateTime('first day of this month 00:00:00');

        $expectedGameSessionsPerMonth = new GameSessionsPerMonth($month, $game);
        $expectedGameSessionsPerMonth->addToDuration(20);

        $oldGameSessionsPerMonth = new GameSessionsPerMonth($month, $game);
        $oldGameSessionsPerMonth->addToDuration(10);

        $overallGameStatsRepositoryMock = $this->createMock(OverallGameStatsRepository::class);
        $repositoryMock = $this->createMock(GameSessionsPerMonthRepository::class);
        $repositoryMock->expects($this->any())
            ->method('findOneBy')
            ->with(['game' => $game, 'month' => $month])
            ->willReturn($oldGameSessionsPerMonth);

        $repositoryMock->expects($this->any())
            ->method('save')
            ->with($expectedGameSessionsPerMonth);

        $basicInformationService = new GameSessionsPerMonthService(
            $repositoryMock,
            $overallGameStatsRepositoryMock
        );

        $this->assertEquals(
            $expectedGameSessionsPerMonth,
            $basicInformationService->updateGameSession(10, $gameSession)
        );
    }
}
