<?php

namespace tests\App\Service\Entity;

use App\Entity\Game;
use App\Repository\GameRepository;
use App\Service\Entity\UpdateGameInformationService;
use App\Service\Transformation\GameInformationService;
use PHPUnit\Framework\TestCase;

/**
 * Class UpdateGameInformationServiceTest
 */
class UpdateGameInformationServiceTest extends TestCase
{
    public function testUpdateGameInformationForExistingGame()
    {
        $game = new game();
        $game->setsteamappid(1);

        $game = new Game();
        $game->setSteamAppId(1);

        $gameInformationServiceMock = $this->createMock(GameInformationService::class);
        $gameInformationServiceMock->expects($this->any())
            ->method('addToGame')
            ->willReturn($game);

        $gameRepositoryMock = $this->createMock(GameRepository::class);
        $gameRepositoryMock->expects($this->any())
            ->method('save')
            ->willReturn(null);

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
