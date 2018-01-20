<?php

namespace tests\App\Service\Steam\Transformation;

use App\Entity\Game;
use App\Entity\GameSession;
use App\Service\Steam\Transformation\RecentlyPlayedGamesService;
use PHPUnit\Framework\TestCase;

/**
 * Class RecentlyPlayedServiceTest
 */
class RecentlyPlayedServiceTest extends TestCase
{
    public function testSortRecentlyPlayedByLastSession()
    {
        $gameWithNewerSession = new Game();
        $gameWithNewerSession->setId(1);
        $newerSession = $this->createMock(GameSession::class);
        $newerSession->expects($this->any())
            ->method('getCreatedAt')
            ->willReturn('2018-02-01 20:00:52.298089');
        $gameWithNewerSession->addGameSession($newerSession);

        $gameWithOlderSession = new Game();
        $gameWithOlderSession->setId(2);
        $olderSession = $this->createMock(GameSession::class);
        $olderSession->expects($this->any())
            ->method('getCreatedAt')
            ->willReturn('2018-01-11 17:21:01.106201');
        $gameWithOlderSession->addGameSession($olderSession);

        $recentlyPlayedService = new RecentlyPlayedGamesService();

        $actualArray = $recentlyPlayedService->sortRecentlyPlayedGamesByLastSession(
            [$gameWithOlderSession, $gameWithNewerSession]
        );

        $this->assertEquals(1, $actualArray[0]->getId());
        $this->assertEquals(2, $actualArray[1]->getId());
    }
}
