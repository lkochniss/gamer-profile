<?php

namespace tests\App\Service\Steam\Entity;

use App\Entity\Game;
use App\Entity\GameInformation;
use App\Repository\GameRepository;
use App\Service\Steam\Entity\UpdateGameInformationService;
use App\Service\Steam\Transformation\GameInformationService;
use PHPUnit\Framework\TestCase;

/**
 * Class UpdateGameInformationServiceTest
 */
class UpdateGameInformationServiceTest extends TestCase
{
    public function testUpdateGameInformationForExistingGame()
    {
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

        $game = new Game();
        $game->setName('Demo Game');
        $gameRepositoryMock = $this->createMock(GameRepository::class);
        $gameRepositoryMock->expects($this->any())
            ->method('findOneBySteamAppId')
            ->with(1)
            ->willReturn($game);

        $updateGameInformationService = new UpdateGameInformationService(
            $gameInformationServiceMock,
            $gameRepositoryMock
        );

        $this->assertEquals('U', $updateGameInformationService->updateGameInformationForSteamAppId(1));
    }
}
