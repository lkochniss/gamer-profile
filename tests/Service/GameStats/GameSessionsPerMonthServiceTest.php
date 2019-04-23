<?php

namespace App\Tests\Service\GameStats;

use App\Entity\Game;
use App\Entity\GameSession;
use App\Entity\GameSessionsPerMonth;
use App\Repository\GameSessionsPerMonthRepository;
use App\Service\GameStats\GameSessionsPerMonthService;
use PHPUnit\Framework\TestCase;

class GameSessionsPerMonthServiceTest extends TestCase
{
    public function testAddGameShouldGetAGameSessionPerMonth(): void
    {
        $game = new Game(1);
        $steamUserId = 2;

        $month = new \DateTime('first day of this month 00:00:00');

        $repositoryMock = $this->createMock(GameSessionsPerMonthRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with([
                'month' => $month,
                'game' => $game,
                'steamUserId' => $steamUserId
            ]);

        $service = new GameSessionsPerMonthService($repositoryMock);
        $service->addGameSession(new GameSession($game, $steamUserId));
    }

    public function testAddGameShouldSaveAGameSessionPerMonth(): void
    {
        $game = new Game(1);
        $steamUserId = 2;

        $month = new \DateTime('first day of this month 00:00:00');

        $repositoryMock = $this->createMock(GameSessionsPerMonthRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with([
                'month' => $month,
                'game' => $game,
                'steamUserId' => $steamUserId
            ])
            ->willReturn(new GameSessionsPerMonth($month, $game, $steamUserId));

        $expectedEntity = new GameSessionsPerMonth($month, $game, $steamUserId);
        $expectedEntity->addToDuration(10);

        $repositoryMock->expects($this->once())
            ->method('save')
            ->with($expectedEntity);

        $service = new GameSessionsPerMonthService($repositoryMock);

        $gameSession = new GameSession($game, $steamUserId);
        $gameSession->addDuration(10);

        $service->addGameSession($gameSession);
    }

    public function testAddGameShouldCreateNewEntityIfNotExisting(): void
    {
        $game = new Game(1);
        $steamUserId = 2;

        $month = new \DateTime('first day of this month 00:00:00');

        $repositoryMock = $this->createMock(GameSessionsPerMonthRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with([
                'month' => $month,
                'game' => $game,
                'steamUserId' => $steamUserId
            ]);

        $expectedEntity = new GameSessionsPerMonth($month, $game, $steamUserId);
        $expectedEntity->addToDuration(10);

        $repositoryMock->expects($this->once())
            ->method('save')
            ->with($expectedEntity);

        $service = new GameSessionsPerMonthService($repositoryMock);

        $gameSession = new GameSession($game, $steamUserId);
        $gameSession->addDuration(10);

        $service->addGameSession($gameSession);
    }

    public function testUpdateGameShouldGetAGameSessionPerMonth(): void
    {
        $game = new Game(1);
        $steamUserId = 2;

        $month = new \DateTime('first day of this month 00:00:00');

        $repositoryMock = $this->createMock(GameSessionsPerMonthRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with([
                'month' => $month,
                'game' => $game,
                'steamUserId' => $steamUserId
            ]);

        $service = new GameSessionsPerMonthService($repositoryMock);
        $service->updateGameSession(10, new GameSession($game, $steamUserId));
    }

    public function testUpdateGameShouldSaveAGameSessionPerMonth(): void
    {
        $game = new Game(1);
        $steamUserId = 2;

        $month = new \DateTime('first day of this month 00:00:00');

        $repositoryMock = $this->createMock(GameSessionsPerMonthRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with([
                'month' => $month,
                'game' => $game,
                'steamUserId' => $steamUserId
            ])
            ->willReturn(new GameSessionsPerMonth($month, $game, $steamUserId));

        $expectedEntity = new GameSessionsPerMonth($month, $game, $steamUserId);
        $expectedEntity->addToDuration(10);

        $repositoryMock->expects($this->once())
            ->method('save')
            ->with($expectedEntity);

        $service = new GameSessionsPerMonthService($repositoryMock);

        $gameSession = new GameSession($game, $steamUserId);

        $service->updateGameSession(10, $gameSession);
    }
}
