<?php

namespace App\Tests\Service\Steam;

use App\Entity\Achievement;
use App\Entity\Game;
use App\Entity\GameStats;
use App\Entity\JSON\JsonGame;
use App\Entity\Playtime;
use App\Repository\GameStatsRepository;
use App\Service\Steam\CompareGamesForUserService;
use App\Service\Transformation\GameUserInformationService;
use PHPUnit\Framework\TestCase;

class CompareGamesForUsersServiceTest extends TestCase
{
    private $myUserId = 1;
    private $friendsUserId = 2;

    public function testCompareMyGamesWithFriendShouldGetGameStats(): void
    {
        $serviceMock = $this->createMock(GameUserInformationService::class);
        $repositoryMock = $this->createMock(GameStatsRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findBy')
            ->with(['steamUserId' => $this->myUserId])
            ->willReturn([]);

        $service = new CompareGamesForUserService($serviceMock, $repositoryMock);
        $service->compareMyGamesWithFriend($this->myUserId, $this->friendsUserId);
    }

    public function testCompareMyGamesWithFriendShouldGetFriendsGames(): void
    {
        $serviceMock = $this->createMock(GameUserInformationService::class);
        $serviceMock->expects($this->once())
            ->method('getAllGames')
            ->with($this->friendsUserId)
            ->willReturn([]);

        $repositoryMock = $this->createMock(GameStatsRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findBy')
            ->with(['steamUserId' => $this->myUserId])
            ->willReturn([]);

        $service = new CompareGamesForUserService($serviceMock, $repositoryMock);
        $service->compareMyGamesWithFriend($this->myUserId, $this->friendsUserId);
    }

    public function testCompareMyGamesWithFriendShouldPassEqualGames(): void
    {
        $serviceMock = $this->createMock(GameUserInformationService::class);
        $serviceMock->expects($this->once())
            ->method('getAllGAmes')
            ->with($this->friendsUserId)
            ->willReturn([
                '222' => new JsonGame([]),
                '233' => new JsonGame([]),
                '244' => new JsonGame([]),
            ]);

        $expectedGame = new Game(222);
        $repositoryMock = $this->createMock(GameStatsRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findBy')
            ->with(['steamUserId' => $this->myUserId])
            ->willReturn([
                new GameStats(
                    $this->myUserId,
                    $expectedGame,
                    new Achievement($this->myUserId, $expectedGame),
                    new Playtime($this->myUserId, $expectedGame)
                ),
                new GameStats(
                    $this->myUserId,
                    new Game(333),
                    new Achievement($this->myUserId, new Game(333)),
                    new Playtime($this->myUserId, new Game(333))
                ),
                new GameStats(
                    $this->myUserId,
                    new Game(444),
                    new Achievement($this->myUserId, new Game(444)),
                    new Playtime($this->myUserId, new Game(444))
                ),
            ]);

        $service = new CompareGamesForUserService($serviceMock, $repositoryMock);
        $actualGames = $service->compareMyGamesWithFriend($this->myUserId, $this->friendsUserId);
        $this->assertEquals([$expectedGame], $actualGames);
    }
}
