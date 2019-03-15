<?php

namespace App\Tests\Service\GameStats;

use App\Entity\Playtime;
use App\Entity\Game;
use App\Entity\JSON\JsonPlaytime;
use App\Entity\User;
use App\Repository\PlaytimeRepository;
use App\Service\GameStats\PlaytimeService;
use App\Service\Transformation\GameUserInformationService;
use PHPUnit\Framework\TestCase;

class PlaytimeServiceTest extends TestCase
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var Game
     */
    private $game;

    public function setUp()
    {
        $this->user = new User(1);
        $this->game = new Game(2);
    }

    public function testPlaytimeCreateShouldCallPlaytimeRepository(): void
    {
        $PlaytimeRepositoryMock = $this->createMock(PlaytimeRepository::class);
        $PlaytimeRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['game' => $this->game, 'user' => $this->user]);

        $gameUserInformationServiceMock = $this->createMock(GameUserInformationService::class);

        $createPlaytimesService = new PlaytimeService($gameUserInformationServiceMock, $PlaytimeRepositoryMock);
        $createPlaytimesService->create($this->user, $this->game);
    }

    public function testPlaytimeCreateShouldReturnExistingPlaytimes(): void
    {
        $expectedPlaytime = new Playtime($this->user, $this->game);
        $PlaytimeRepositoryMock = $this->createMock(PlaytimeRepository::class);
        $PlaytimeRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['game' => $this->game, 'user' => $this->user])
            ->willReturn($expectedPlaytime);

        $gameUserInformationServiceMock = $this->createMock(GameUserInformationService::class);

        $createPlaytimesService = new PlaytimeService($gameUserInformationServiceMock, $PlaytimeRepositoryMock);
        $this->assertEquals($expectedPlaytime, $createPlaytimesService->create($this->user, $this->game));
    }

    public function testPlaytimeCreateShouldCallGameUserInformationService(): void
    {
        $PlaytimeRepositoryMock = $this->createMock(PlaytimeRepository::class);
        $PlaytimeRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['game' => $this->game, 'user' => $this->user])
            ->willReturn(null);

        $gameUserInformationServiceMock = $this->createMock(GameUserInformationService::class);

        $createPlaytimesService = new PlaytimeService($gameUserInformationServiceMock, $PlaytimeRepositoryMock);
        $createPlaytimesService->create($this->user, $this->game);
    }

    public function testPlaytimeCreateShouldPersistPlaytime(): void
    {
        $expectedPlaytime = new Playtime($this->user, $this->game);
        $expectedPlaytime->setRecentPlaytime(0);
        $expectedPlaytime->setOverallPlaytime(0);

        $PlaytimeRepositoryMock = $this->createMock(PlaytimeRepository::class);
        $PlaytimeRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['game' => $this->game, 'user' => $this->user])
            ->willReturn(null);

        $gameUserInformationServiceMock = $this->createMock(GameUserInformationService::class);
        $gameUserInformationServiceMock->expects($this->once())
            ->method('getPlaytimeForGame')
            ->willReturn(new JsonPlaytime());

        $PlaytimeRepositoryMock->expects($this->once())
            ->method('save')
            ->with($expectedPlaytime);

        $createPlaytimesService = new PlaytimeService($gameUserInformationServiceMock, $PlaytimeRepositoryMock);
        $createPlaytimesService->create($this->user, $this->game);
    }

    public function testPlaytimeCreateShouldReturnPlaytime(): void
    {
        $expectedPlaytime = new Playtime($this->user, $this->game);
        $expectedPlaytime->setRecentPlaytime(10);
        $expectedPlaytime->setOverallPlaytime(20);

        $PlaytimeRepositoryMock = $this->createMock(PlaytimeRepository::class);
        $PlaytimeRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['game' => $this->game, 'user' => $this->user])
            ->willReturn(null);

        $gameUserInformationServiceMock = $this->createMock(GameUserInformationService::class);
        $gameUserInformationServiceMock->expects($this->once())
            ->method('getPlaytimeForGame')
            ->willReturn(new JsonPlaytime([
                'playtime_forever' => 20,
                'playtime_2weeks' => 10,
            ]));

        $createPlaytimesService = new PlaytimeService($gameUserInformationServiceMock, $PlaytimeRepositoryMock);
        $this->assertEquals($expectedPlaytime, $createPlaytimesService->create($this->user, $this->game));
    }

    public function testPlaytimeUpdateShouldPersistPlaytime(): void
    {
        $expectedPlaytime = new Playtime($this->user, $this->game);
        $expectedPlaytime->setRecentPlaytime(0);
        $expectedPlaytime->setRecentPlaytime(0);

        $PlaytimeRepositoryMock = $this->createMock(PlaytimeRepository::class);

        $gameUserInformationServiceMock = $this->createMock(GameUserInformationService::class);
        $gameUserInformationServiceMock->expects($this->once())
            ->method('getPlaytimeForGame')
            ->willReturn(new JsonPlaytime());

        $PlaytimeRepositoryMock->expects($this->once())
            ->method('save')
            ->with($expectedPlaytime);

        $createPlaytimesService = new PlaytimeService($gameUserInformationServiceMock, $PlaytimeRepositoryMock);
        $createPlaytimesService->update(new Playtime($this->user, $this->game));
    }

    public function testPlaytimeUpdateShouldReturnPlaytime(): void
    {
        $expectedPlaytime = new Playtime($this->user, $this->game);
        $expectedPlaytime->setRecentPlaytime(10);
        $expectedPlaytime->setOverallPlaytime(20);

        $PlaytimeRepositoryMock = $this->createMock(PlaytimeRepository::class);

        $gameUserInformationServiceMock = $this->createMock(GameUserInformationService::class);
        $gameUserInformationServiceMock->expects($this->once())
            ->method('getPlaytimeForGame')
            ->willReturn(new JsonPlaytime([
                'playtime_forever' => 20,
                'playtime_2weeks' => 10,
            ]));

        $createPlaytimesService = new PlaytimeService($gameUserInformationServiceMock, $PlaytimeRepositoryMock);
        $this->assertEquals($expectedPlaytime, $createPlaytimesService->update(new Playtime($this->user, $this->game)));
    }
}
