<?php

namespace tests\App\Service\Stats;

use App\Entity\Game;
use App\Entity\GameSession;
use App\Entity\PlaytimePerMonth;
use App\Entity\User;
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
        $user = new User(1);

        $expectedPlaytimePerMonth = new PlaytimePerMonth($month, $user);
        $expectedPlaytimePerMonth->addToDuration(10);
        $expectedPlaytimePerMonth->addSession();

        $repositoryMock = $this->createMock(PlaytimePerMonthRepository::class);
        $repositoryMock->expects($this->any())
            ->method('findOneBy')
            ->with(['month' => $month])
            ->willReturn(new PlaytimePerMonth($month, $user));

        $game = new Game(1);
        $gameSession = new GameSession($game, $user, $month);
        $gameSession->setDuration(10);

        $playtimePerMonthService = new PlaytimePerMonthService($repositoryMock);
        $actualPlaytimePerMonth = $playtimePerMonthService->addSession($gameSession);

        $this->assertEquals($expectedPlaytimePerMonth, $actualPlaytimePerMonth);
    }

    public function testUpdateSession(): void
    {
        $month = new \DateTime('first day of this month 00:00:00');
        $user = new User(1);

        $expectedPlaytimePerMonth = new PlaytimePerMonth($month, $user);
        $expectedPlaytimePerMonth->addToDuration(20);
        $expectedPlaytimePerMonth->addSession();

        $oldSessionPerMonth = new PlaytimePerMonth($month, $user);
        $oldSessionPerMonth->addToDuration(10);
        $oldSessionPerMonth->addSession();

        $repositoryMock = $this->createMock(PlaytimePerMonthRepository::class);
        $repositoryMock->expects($this->any())
            ->method('findOneBy')
            ->with(['month' => $month])
            ->willReturn($oldSessionPerMonth);

        $game = new Game(1);
        $gameSession = new GameSession($game, $user, $month);
        $gameSession->setDuration(10);

        $playtimePerMonthService = new PlaytimePerMonthService($repositoryMock);
        $actualPlaytimePerMonth = $playtimePerMonthService->updateSession(10, $user);

        $this->assertEquals($expectedPlaytimePerMonth, $actualPlaytimePerMonth);
    }
}
