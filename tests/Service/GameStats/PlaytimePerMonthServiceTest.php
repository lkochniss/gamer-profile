<?php

namespace App\Tests\Service\GameStats;

use App\Entity\Game;
use App\Entity\GameSession;
use App\Entity\PlaytimePerMonth;
use App\Repository\PlaytimePerMonthRepository;
use App\Service\GameStats\PlaytimePerMonthService;
use PHPUnit\Framework\TestCase;

class PlaytimePerMonthServiceTest extends TestCase
{
    public function testAddSessionShouldCallTheRepositoryCorrectly(): void
    {
        $steamUserId = 1;
        $month = new \DateTime('first day of this month 00:00:00');

        $repositoryMock = $this->createMock(PlaytimePerMonthRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with([
                'month' => $month,
                'steamUserId' => $steamUserId
            ]);

        $service = new PlaytimePerMonthService($repositoryMock);
        $service->addSession(new GameSession(new Game(2), $steamUserId));
    }

    public function testAddSessionShouldSaveGameSessionPerMonth(): void
    {
        $steamUserId = 1;
        $month = new \DateTime('first day of this month 00:00:00');
        $game = new Game(2);

        $gameSession = new GameSession($game, $steamUserId);
        $gameSession->setDuration(10);

        $expectedSessionPerMonth = new PlaytimePerMonth($month, $steamUserId);
        $expectedSessionPerMonth->addSession();
        $expectedSessionPerMonth->addToDuration($gameSession->getDuration());

        $repositoryMock = $this->createMock(PlaytimePerMonthRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with([
                'month' => $month,
                'steamUserId' => $steamUserId
            ])
            ->willReturn(new PlaytimePerMonth($month, $steamUserId));

        $service = new PlaytimePerMonthService($repositoryMock);
        $service->addSession($gameSession);
    }

    public function testAddSessionShouldCreateEntityIfNoneExists(): void
    {
        $steamUserId = 1;
        $month = new \DateTime('first day of this month 00:00:00');
        $game = new Game(2);

        $gameSession = new GameSession($game, $steamUserId);
        $gameSession->setDuration(10);

        $expectedSessionPerMonth = new PlaytimePerMonth($month, $steamUserId);
        $expectedSessionPerMonth->addSession();
        $expectedSessionPerMonth->addToDuration($gameSession->getDuration());

        $repositoryMock = $this->createMock(PlaytimePerMonthRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with([
                'month' => $month,
                'steamUserId' => $steamUserId
            ]);

        $service = new PlaytimePerMonthService($repositoryMock);
        $service->addSession($gameSession);
    }

    public function testUpdateSessionShouldCallTheRepositoryCorrectly(): void
    {
        $steamUserId = 1;
        $month = new \DateTime('first day of this month 00:00:00');

        $repositoryMock = $this->createMock(PlaytimePerMonthRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with([
                'month' => $month,
                'steamUserId' => $steamUserId
            ]);

        $service = new PlaytimePerMonthService($repositoryMock);
        $service->updateSession(10, $steamUserId);
    }

    public function testUpdateSessionShouldSaveGameSessionPerMonth(): void
    {
        $steamUserId = 1;
        $month = new \DateTime('first day of this month 00:00:00');
        $duration = 10;

        $expectedSessionPerMonth = new PlaytimePerMonth($month, $steamUserId);
        $expectedSessionPerMonth->addSession();
        $expectedSessionPerMonth->addToDuration($duration);

        $repositoryMock = $this->createMock(PlaytimePerMonthRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with([
                'month' => $month,
                'steamUserId' => $steamUserId
            ])
            ->willReturn(new PlaytimePerMonth($month, $steamUserId));

        $service = new PlaytimePerMonthService($repositoryMock);
        $service->updateSession($duration, $steamUserId);
    }

    public function testUpdateSessionShouldCreateEntityIfNoneExists(): void
    {
        $steamUserId = 1;
        $month = new \DateTime('first day of this month 00:00:00');
        $duration = 10;

        $expectedSessionPerMonth = new PlaytimePerMonth($month, $steamUserId);
        $expectedSessionPerMonth->addToDuration($duration);

        $repositoryMock = $this->createMock(PlaytimePerMonthRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with([
                'month' => $month,
                'steamUserId' => $steamUserId
            ]);

        $service = new PlaytimePerMonthService($repositoryMock);
        $service->updateSession($duration, $steamUserId);
    }
}
