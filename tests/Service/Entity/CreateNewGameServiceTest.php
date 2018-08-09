<?php

namespace tests\App\Service\Entity;

use App\Entity\Game;
use App\Repository\GameRepository;
use App\Service\Entity\GameService;
use App\Service\Transformation\GameInformationService;
use App\Service\Transformation\GameUserInformationService;
use PHPUnit\Framework\TestCase;

/**
 * Class CreateNewGameServiceTest
 */
class CreateNewGameServiceTest extends TestCase
{
    public function testSkipCreateGameIfGameExist(): void
    {
        $ownedGamesServiceMock = $this->createMock(GameUserInformationService::class);
        $ownedGamesServiceMock->expects($this->never())
            ->method('getUserInformationEntityForSteamAppId')
            ->willReturn(null);

        $gameInformationServiceMock = $this->createMock(GameInformationService::class);
        $gameInformationServiceMock->expects($this->never())
            ->method('getGameInformationEntityForSteamAppId')
            ->with(1)
            ->willReturn(null);

        $game = new Game();
        $game->setId(1);
        $game->setName('Demo Game');
        $gameRepositoryMock = $this->createMock(GameRepository::class);
        $gameRepositoryMock->expects($this->any())
            ->method('findOneBySteamAppId')
            ->with(1)
            ->willReturn($game);

        $createNewGameService = new GameService(
            $ownedGamesServiceMock,
            $gameInformationServiceMock,
            $gameRepositoryMock
        );

        $this->assertEquals($game, $createNewGameService->createGameIfNotExist(1));
    }

    public function testCreateGameIfGameNotExist(): void
    {
        $game = new Game();
        $game->setSteamAppId(1);

        $gameInformationServiceMock = $this->createMock(GameInformationService::class);
        $gameInformationServiceMock->expects($this->any())
            ->method('addToGame')
            ->willReturn($game);

        $userInformationServiceMock = $this->createMock(GameUserInformationService::class);
        $userInformationServiceMock->expects($this->any())
            ->method('addPlaytime')
            ->willReturn($game);

        $userInformationServiceMock->expects($this->any())
            ->method('addAchievements')
            ->willReturn($game);

        $gameRepositoryMock = $this->createMock(GameRepository::class);
        $gameRepositoryMock->expects($this->any())
            ->method('save')
            ->willReturn(null);

        $createNewGameService = new GameService(
            $userInformationServiceMock,
            $gameInformationServiceMock,
            $gameRepositoryMock
        );

        $this->assertEquals($game, $createNewGameService->createGameIfNotExist(1));
    }
}
