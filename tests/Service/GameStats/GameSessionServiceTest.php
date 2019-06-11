<?php

namespace App\Tests\Service\GameStats;

use App\Entity\Game;
use App\Entity\GameSession;
use App\Repository\GameSessionRepository;
use App\Service\GameStats\GameSessionService;
use PHPUnit\Framework\TestCase;

class GameSessionServiceTest extends TestCase
{
    public function testGetTodaysGameSessionShouldCallRepositoryCorrectly(): void
    {
        $steamUserId = 1;
        $game = new Game(2);
        $today = new \DateTime('today 00:00:00');

        $repositoryMock = $this->createMock(GameSessionRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with([
                'steamUserId' => $steamUserId,
                'game' => $game,
                'date' => $today
            ]);

        $service = new GameSessionService($repositoryMock);
        $service->getTodaysGameSession($steamUserId, $game);
    }

    public function testGetTodaysGameSessionShouldReturnExistingGameSession(): void
    {
        $steamUserId = 1;
        $game = new Game(2);
        $today = new \DateTime('today 00:00:00');

        $expectedGameSession = new GameSession($game, $steamUserId);
        $expectedGameSession->setDuration(20);

        $repositoryMock = $this->createMock(GameSessionRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with([
                'steamUserId' => $steamUserId,
                'game' => $game,
                'date' => $today
            ])
            ->willReturn($expectedGameSession);

        $service = new GameSessionService($repositoryMock);
        $actualGameSession = $service->getTodaysGameSession($steamUserId, $game);

        $this->assertEquals($expectedGameSession, $actualGameSession);
    }

    public function testGetTodaysGameSessionShouldReturnNewGameSession(): void
    {
        $steamUserId = 1;
        $game = new Game(2);
        $today = new \DateTime('today 00:00:00');

        $expectedGameSession = new GameSession($game, $steamUserId);

        $repositoryMock = $this->createMock(GameSessionRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with([
                'steamUserId' => $steamUserId,
                'game' => $game,
                'date' => $today
            ])
            ->willReturn(null);

        $service = new GameSessionService($repositoryMock);
        $actualGameSession = $service->getTodaysGameSession($steamUserId, $game);

        $this->assertEquals($expectedGameSession, $actualGameSession);
    }

    public function testUpdateGameSessionShouldChangeDurationWithHigherNewTime()
    {
        $oldTime = 20;
        $newTime = 40;

        $steamUserId = 1;
        $game = new Game(2);

        $gameSession = new GameSession($game, $steamUserId);

        $expectedSession = new GameSession($game, $steamUserId);
        $expectedSession->setDuration(20);

        $repositoryMock = $this->createMock(GameSessionRepository::class);
        $repositoryMock->expects($this->once())
            ->method('save')
            ->with($expectedSession);

        $service = new GameSessionService($repositoryMock);
        $service->updateGameSession($gameSession, $oldTime, $newTime);
    }

    public function testUpdateGameSessionShouldSkipWithLowerNewTime()
    {
        $oldTime = 40;
        $newTime = 40;

        $steamUserId = 1;
        $game = new Game(2);

        $gameSession = new GameSession($game, $steamUserId);

        $expectedSession = new GameSession($game, $steamUserId);
        $expectedSession->setDuration(20);

        $repositoryMock = $this->createMock(GameSessionRepository::class);
        $repositoryMock->expects($this->never())
            ->method('save');

        $service = new GameSessionService($repositoryMock);
        $service->updateGameSession($gameSession, $oldTime, $newTime);
    }

    public function testUpdateGameSessionShouldThrowErrorOnTooHighDiff()
    {
        $oldTime = 0;
        $newTime = 100;

        $steamUserId = 1;
        $game = new Game(2);
        $game->setName('Test Game');

        $gameSession = new GameSession($game, $steamUserId);

        $repositoryMock = $this->createMock(GameSessionRepository::class);
        $repositoryMock->expects($this->never())
            ->method('save');

        $this->expectException(\LogicException::class);
        $service = new GameSessionService($repositoryMock);
        $service->updateGameSession($gameSession, $oldTime, $newTime);
    }
}
