<?php

namespace tests\App\Service\Stats;

use App\Entity\Game;
use App\Entity\GameSession;
use App\Entity\OverallGameStats;
use App\Entity\User;
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
        $user = new User(1);
        $expectedOverallGameStats = new OverallGameStats($user);
        $expectedOverallGameStats->setGameSessions(1);

        $gameSessionRepositoryMock = $this->createMock(GameSessionRepository::class);
        $gameSessionRepositoryMock->expects($this->any())
            ->method('findAll')
            ->willReturn([new GameSession(new Game(1), $user, new \DateTime())]);

        $overallGameStatsRepositoryMock = $this->createMock(OverallGameStatsRepository::class);
        $overallGameStatsRepositoryMock->expects($this->any())
            ->method('findOneBy')
            ->with(['user' => $user])
            ->willReturn(new OverallGameStats($user));

        $overallGameStatsRepositoryMock->expects($this->any())
            ->method('save')
            ->with($expectedOverallGameStats);

        $gameSessionService = new GameSessionService($gameSessionRepositoryMock, $overallGameStatsRepositoryMock);

        $this->assertEquals(
            'U',
            $gameSessionService->recalculate($user)
        );
    }
}
