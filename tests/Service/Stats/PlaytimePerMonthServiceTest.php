<?php

namespace tests\App\Service\Stats;

use App\Entity\Game;
use App\Entity\GameSession;
use App\Entity\PlaytimePerMonth;
use App\Repository\PlaytimePerMonthRepository;
use App\Service\Stats\PlaytimePerMonthService;
use PHPUnit\Framework\TestCase;

/**
 * Class PlaytimePerMonthServiceTest
 */
class PlaytimePerMonthServiceTest extends TestCase
{
    public function testAddSession(): void
    {
        $month = new \DateTime('first day of this month 00:00:00');

        $expectedPlaytimePerMonth = new PlaytimePerMonth($month);
        $expectedPlaytimePerMonth->addToDuration(10);
        $expectedPlaytimePerMonth->addSession();

        $repositoryMock = $this->createMock(PlaytimePerMonthRepository::class);
        $repositoryMock->expects($this->any())
            ->method('findOneBy')
            ->with(['month' => $month])
            ->willReturn(new PlaytimePerMonth($month));

        $gameSession = new GameSession(new Game());
        $gameSession->setDuration(10);

        $playtimePerMonthService = new PlaytimePerMonthService($repositoryMock);
        $actualPlaytimePerMonth = $playtimePerMonthService->addSession($gameSession);

        $this->assertEquals($expectedPlaytimePerMonth, $actualPlaytimePerMonth);
    }

    public function testUpdateSession(): void
    {
        $month = new \DateTime('first day of this month 00:00:00');

        $expectedPlaytimePerMonth = new PlaytimePerMonth($month);
        $expectedPlaytimePerMonth->addToDuration(20);
        $expectedPlaytimePerMonth->addSession();

        $oldSessionPerMonth = new PlaytimePerMonth($month);
        $oldSessionPerMonth->addToDuration(10);
        $oldSessionPerMonth->addSession();

        $repositoryMock = $this->createMock(PlaytimePerMonthRepository::class);
        $repositoryMock->expects($this->any())
            ->method('findOneBy')
            ->with(['month' => $month])
            ->willReturn($oldSessionPerMonth);

        $gameSession = new GameSession(new Game());
        $gameSession->setDuration(10);

        $playtimePerMonthService = new PlaytimePerMonthService($repositoryMock);
        $actualPlaytimePerMonth = $playtimePerMonthService->updateSession(10, $gameSession);

        $this->assertEquals($expectedPlaytimePerMonth, $actualPlaytimePerMonth);
    }
}
