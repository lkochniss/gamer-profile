<?php

namespace tests\App\Service\Stats;

use App\Entity\Game;
use App\Entity\GameSession;
use App\Entity\OverallGameStats;
use App\Repository\GameSessionRepository;
use App\Repository\OverallGameStatsRepository;
use App\Service\Stats\GameSessionService;
use PHPUnit\Framework\TestCase;

/**
 * Class GameSessionServiceTest
 */
class GameSessionServiceTest extends TestCase
{
    public function testRecalculate(): void
    {
        $expectedOverallGameStats = new OverallGameStats();
        $expectedOverallGameStats->setGameSessions(1);

        $gameSessionRepositoryMock = $this->createMock(GameSessionRepository::class);
        $gameSessionRepositoryMock->expects($this->any())
            ->method('findAll')
            ->willReturn([new GameSession(new Game())]);

        $overallGameStatsRepositoryMock = $this->createMock(OverallGameStatsRepository::class);
        $overallGameStatsRepositoryMock->expects($this->any())
            ->method('findOneBy')
            ->with(['identifier' => getenv('STEAM_USER_ID')])
            ->willReturn(new OverallGameStats());

        $overallGameStatsRepositoryMock->expects($this->any())
            ->method('save')
            ->with($expectedOverallGameStats);

        $gameSessionService = new GameSessionService($gameSessionRepositoryMock, $overallGameStatsRepositoryMock);

        $this->assertEquals(
            'U',
            $gameSessionService->recalculate()
        );
    }
}
