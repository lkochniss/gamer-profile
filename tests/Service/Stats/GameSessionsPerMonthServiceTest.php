<?php

namespace tests\App\Service\Stats;

use App\Entity\Game;
use App\Entity\GameSession;
use App\Entity\GameSessionsPerMonth;
use App\Entity\OverallGameStats;
use App\Entity\User;
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
        $user = new User(1);
        $expectedOverallGameStats = new OverallGameStats($user);
        $expectedOverallGameStats->addGameSessions();

        $overallGameStatsRepositoryMock = $this->createMock(OverallGameStatsRepository::class);
        $overallGameStatsRepositoryMock->expects($this->any())
            ->method('findOneBy')
            ->with(['user' => $user])
            ->willReturn(new OverallGameStats($user));

        $overallGameStatsRepositoryMock->expects($this->any())
            ->method('save')
            ->with($expectedOverallGameStats);

        $repositoryMock = $this->createMock(GameSessionsPerMonthRepository::class);

        $gameSessionsPerMonthService = new GameSessionsPerMonthService(
            $repositoryMock,
            $overallGameStatsRepositoryMock
        );

        $this->assertEquals(
            $expectedOverallGameStats,
            $gameSessionsPerMonthService->addSessionToOverallGameStats($user)
        );
    }

    public function testAddGameSession(): void
    {
        $game = new Game(1);
        $user = new User(1);
        $month = new \DateTime('first day of this month 00:00:00');

        $gameSession = new GameSession($game, $user, $month);
        $gameSession->setDuration(10);


        $expectedGameSessionsPerMonth = new GameSessionsPerMonth($month, $game, $user);
        $expectedGameSessionsPerMonth->addToDuration(10);

        $overallGameStatsRepositoryMock = $this->createMock(OverallGameStatsRepository::class);
        $repositoryMock = $this->createMock(GameSessionsPerMonthRepository::class);
        $repositoryMock->expects($this->any())
            ->method('findOneBy')
            ->with(['game' => $game, 'month' => $month])
            ->willReturn(new GameSessionsPerMonth($month, $game, $user));

        $repositoryMock->expects($this->any())
            ->method('save')
            ->with($expectedGameSessionsPerMonth);

        $gameSessionsPerMonthService = new GameSessionsPerMonthService(
            $repositoryMock,
            $overallGameStatsRepositoryMock
        );

        $this->assertEquals(
            $expectedGameSessionsPerMonth,
            $gameSessionsPerMonthService->addGameSession($gameSession)
        );
    }

    public function testUpdateGameSession(): void
    {
        $game = new Game(1);
        $user = new User(1);
        $month = new \DateTime('first day of this month 00:00:00');

        $gameSession = new GameSession($game, $user, $month);
        $gameSession->setDuration(20);

        $expectedGameSessionsPerMonth = new GameSessionsPerMonth($month, $game, $user);
        $expectedGameSessionsPerMonth->addToDuration(20);

        $oldGameSessionsPerMonth = new GameSessionsPerMonth($month, $game, $user);
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

        $gameSessionsPerMonthService = new GameSessionsPerMonthService(
            $repositoryMock,
            $overallGameStatsRepositoryMock
        );

        $this->assertEquals(
            $expectedGameSessionsPerMonth,
            $gameSessionsPerMonthService->updateGameSession(10, $gameSession)
        );
    }
}
