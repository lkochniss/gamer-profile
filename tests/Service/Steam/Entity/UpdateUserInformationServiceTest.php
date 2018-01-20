<?php

namespace tests\App\Service\Steam\Entity;

use App\Entity\Game;
use App\Entity\UserInformation;
use App\Repository\GameRepository;
use App\Service\Steam\Entity\UpdateUserInformationService;
use App\Service\Steam\Transformation\GameUserInformationService;
use PHPUnit\Framework\TestCase;

/**
 * Class UpdateGameInformationServiceTest
 */
class UpdateUserInformationServiceTest extends TestCase
{
    public function testUpdateGameInformationForExistingGame()
    {
        $userInformationArray = [
            'appid' => 1,
            'playtime_forever' => 0
        ];
        $userInformation = new UserInformation($userInformationArray);
        $gameUserInformationServiceMock = $this->createMock(GameUserInformationService::class);
        $gameUserInformationServiceMock->expects($this->any())
            ->method('getUserInformationEntityForSteamAppId')
            ->with(1)
            ->willReturn($userInformation);

        $game = new Game();
        $game->setName('Demo Game');
        $gameRepositoryMock = $this->createMock(GameRepository::class);
        $gameRepositoryMock->expects($this->any())
            ->method('findOneBySteamAppId')
            ->with(1)
            ->willReturn($game);

        $updateGameInformationService = new UpdateUserInformationService(
            $gameUserInformationServiceMock,
            $gameRepositoryMock
        );

        $this->assertEquals('U', $updateGameInformationService->updateUserInformationForSteamAppId(1));
    }
}
