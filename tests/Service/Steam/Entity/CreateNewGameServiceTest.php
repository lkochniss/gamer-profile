<?php

namespace tests\App\Service\Steam\Entity;

use App\Entity\Game;
use App\Entity\GameInformation;
use App\Entity\UserInformation;
use App\Repository\GameRepository;
use App\Service\Steam\Entity\CreateNewGameService;
use App\Service\Steam\Transformation\GameInformationService;
use App\Service\Steam\Transformation\GameUserInformationService;
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

        $createNewGameService = new CreateNewGameService(
            $ownedGamesServiceMock,
            $gameInformationServiceMock,
            $gameRepositoryMock
        );

        $this->assertEquals('S', $createNewGameService->createGameIfNotExist(1));
    }

    public function testCreateGameIfGameNotExist(): void
    {
        $userInformationArray = [
            'appid' => 1,
            'playtime_forever' => 0
        ];

        $userInformation = new UserInformation($userInformationArray);

        $ownedGamesServiceMock = $this->createMock(GameUserInformationService::class);
        $ownedGamesServiceMock->expects($this->any())
            ->method('getUserInformationEntityForSteamAppId')
            ->willReturn($userInformation);

        $gameInformationArray = [
            'type' => 'game',
            'name' => 'Demo game',
            'steam_appid' => 1,
            'header_image' => 'http://header.image/src.jpg',
            'price_overview' => [
                'currency' => 'EUR',
                'final' => '1000'
            ],
            'release_date' => [
                'date' => '10 Oct, 2017'
            ]
        ];
        $gameInformation = new GameInformation($gameInformationArray);

        $gameInformationServiceMock = $this->createMock(GameInformationService::class);
        $gameInformationServiceMock->expects($this->any())
            ->method('getGameInformationEntityForSteamAppId')
            ->with(1)
            ->willReturn($gameInformation);

        $gameRepositoryMock = $this->createMock(GameRepository::class);
        $gameRepositoryMock->expects($this->any())
            ->method('findOneBySteamAppId')
            ->with(1)
            ->willReturn(null);

        $createNewGameService = new CreateNewGameService(
            $ownedGamesServiceMock,
            $gameInformationServiceMock,
            $gameRepositoryMock
        );

        $this->assertEquals('N', $createNewGameService->createGameIfNotExist(1));
    }
}
