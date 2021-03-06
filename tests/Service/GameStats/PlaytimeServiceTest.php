<?php

namespace App\Tests\Service\GameStats;

use App\Entity\Playtime;
use App\Entity\Game;
use App\Entity\JSON\JsonPlaytime;
use App\Repository\PlaytimeRepository;
use App\Service\GameStats\PlaytimeService;
use App\Service\GameStats\GameSessionService;
use App\Service\Transformation\GameUserInformationService;
use PHPUnit\Framework\TestCase;

class PlaytimeServiceTest extends TestCase
{
    /**
     * @var string
     */
    private $steamUserId;

    /**
     * @var Game
     */
    private $game;

    public function setUp()
    {
        $this->steamUserId = 1;
        $this->game = new Game(2);
    }

    public function testPlaytimeCreateShouldCallPlaytimeRepository(): void
    {
        $PlaytimeRepositoryMock = $this->createMock(PlaytimeRepository::class);
        $PlaytimeRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['game' => $this->game, 'steamUserId' => $this->steamUserId]);

        $gameUserInformationServiceMock = $this->createMock(GameUserInformationService::class);

        $gameSessionServiceMock = $this->createMock(GameSessionService::class);

        $createPlaytimeService = new PlaytimeService(
            $gameUserInformationServiceMock,
            $PlaytimeRepositoryMock,
            $gameSessionServiceMock
        );
        $createPlaytimeService->create($this->steamUserId, $this->game);
    }

    public function testPlaytimeCreateShouldReturnExistingPlaytimes(): void
    {
        $expectedPlaytime = new Playtime($this->steamUserId, $this->game);
        $PlaytimeRepositoryMock = $this->createMock(PlaytimeRepository::class);
        $PlaytimeRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['game' => $this->game, 'steamUserId' => $this->steamUserId])
            ->willReturn($expectedPlaytime);

        $gameUserInformationServiceMock = $this->createMock(GameUserInformationService::class);

        $gameSessionServiceMock = $this->createMock(GameSessionService::class);

        $createPlaytimeService = new PlaytimeService(
            $gameUserInformationServiceMock,
            $PlaytimeRepositoryMock,
            $gameSessionServiceMock
        );
        $this->assertEquals($expectedPlaytime, $createPlaytimeService->create($this->steamUserId, $this->game));
    }

    public function testPlaytimeCreateShouldCallGameUserInformationService(): void
    {
        $PlaytimeRepositoryMock = $this->createMock(PlaytimeRepository::class);
        $PlaytimeRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['game' => $this->game, 'steamUserId' => $this->steamUserId])
            ->willReturn(null);

        $gameUserInformationServiceMock = $this->createMock(GameUserInformationService::class);

        $gameSessionServiceMock = $this->createMock(GameSessionService::class);

        $createPlaytimeService = new PlaytimeService(
            $gameUserInformationServiceMock,
            $PlaytimeRepositoryMock,
            $gameSessionServiceMock
        );
        $createPlaytimeService->create($this->steamUserId, $this->game);
    }

    public function testPlaytimeCreateShouldPersistPlaytime(): void
    {
        $expectedPlaytime = new Playtime($this->steamUserId, $this->game);
        $expectedPlaytime->setRecentPlaytime(0);
        $expectedPlaytime->setOverallPlaytime(0);

        $PlaytimeRepositoryMock = $this->createMock(PlaytimeRepository::class);
        $PlaytimeRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['game' => $this->game, 'steamUserId' => $this->steamUserId])
            ->willReturn(null);

        $gameUserInformationServiceMock = $this->createMock(GameUserInformationService::class);
        $gameUserInformationServiceMock->expects($this->once())
            ->method('getPlaytimeForGame')
            ->willReturn(new JsonPlaytime());

        $PlaytimeRepositoryMock->expects($this->once())
            ->method('save')
            ->with($expectedPlaytime);

        $gameSessionServiceMock = $this->createMock(GameSessionService::class);

        $createPlaytimeService = new PlaytimeService(
            $gameUserInformationServiceMock,
            $PlaytimeRepositoryMock,
            $gameSessionServiceMock
        );
        $createPlaytimeService->create($this->steamUserId, $this->game);
    }

    public function testPlaytimeCreateShouldReturnPlaytime(): void
    {
        $expectedPlaytime = new Playtime($this->steamUserId, $this->game);
        $expectedPlaytime->setRecentPlaytime(10);
        $expectedPlaytime->setOverallPlaytime(20);

        $PlaytimeRepositoryMock = $this->createMock(PlaytimeRepository::class);
        $PlaytimeRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['game' => $this->game, 'steamUserId' => $this->steamUserId])
            ->willReturn(null);

        $gameUserInformationServiceMock = $this->createMock(GameUserInformationService::class);
        $gameUserInformationServiceMock->expects($this->once())
            ->method('getPlaytimeForGame')
            ->willReturn(new JsonPlaytime([
                'playtime_forever' => 20,
                'playtime_2weeks' => 10,
            ]));

        $gameSessionServiceMock = $this->createMock(GameSessionService::class);

        $createPlaytimeService = new PlaytimeService(
            $gameUserInformationServiceMock,
            $PlaytimeRepositoryMock,
            $gameSessionServiceMock
        );
        $this->assertEquals($expectedPlaytime, $createPlaytimeService->create($this->steamUserId, $this->game));
    }

    public function testPlaytimeUpdateShouldPersistPlaytime(): void
    {
        $expectedPlaytime = new Playtime($this->steamUserId, $this->game);
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

        $gameSessionServiceMock = $this->createMock(GameSessionService::class);

        $createPlaytimeService = new PlaytimeService(
            $gameUserInformationServiceMock,
            $PlaytimeRepositoryMock,
            $gameSessionServiceMock
        );
        $createPlaytimeService->update(new Playtime($this->steamUserId, $this->game));
    }

    public function testPlaytimeUpdateShouldReturnPlaytime(): void
    {
        $expectedPlaytime = new Playtime($this->steamUserId, $this->game);
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

        $gameSessionServiceMock = $this->createMock(GameSessionService::class);

        $createPlaytimeService = new PlaytimeService(
            $gameUserInformationServiceMock,
            $PlaytimeRepositoryMock,
            $gameSessionServiceMock
        );

        $this->assertEquals(
            $expectedPlaytime,
            $createPlaytimeService->update(new Playtime($this->steamUserId, $this->game))
        );
    }
}
