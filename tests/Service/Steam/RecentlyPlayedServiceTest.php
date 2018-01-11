<?php

namespace tests\App\Service\Steam;

use App\Entity\Game;
use App\Entity\GameSession;
use App\Service\Steam\RecentlyPlayedService;
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

        // Ignore it first
        // $gameWithoutSession = new Game();
        // $gameWithoutSession->setId(3);

        $recentlyPlayedService = new RecentlyPlayedService();

        $actualArray = $recentlyPlayedService->sortRecentlyPlayedByLastSession(
            [$gameWithOlderSession, $gameWithNewerSession]
        );

        $this->assertEquals(1, $actualArray[0]->getId());
        $this->assertEquals(2, $actualArray[1]->getId());
        // $this->assertEquals(3, $actualArray[2]->getId());
    }
}
