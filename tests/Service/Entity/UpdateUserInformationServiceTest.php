<?php

namespace tests\App\Service\Entity;

use App\Entity\Game;
use App\Entity\JsonPlaytime;
use App\Repository\GameRepository;
use App\Service\Entity\UpdateUserInformationService;
use App\Service\Transformation\GameUserInformationService;
use PHPUnit\Framework\TestCase;

/**
 * Class UpdateGameInformationServiceTest
 */
class UpdateUserInformationServiceTest extends TestCase
{
    public function testUpdateGameInformationForExistingGame()
    {
        $game = new Game();
        $game->setSteamAppId(1);

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

        $gameRepositoryMock = $this->createMock(GameRepository::class);
        $gameRepositoryMock->expects($this->any())
            ->method('findOneBySteamAppId')
            ->with(1)
            ->willReturn($game);

        $updateGameInformationService = new UpdateUserInformationService(
            $userInformationServiceMock,
            $gameRepositoryMock
        );

        $this->assertEquals('U', $updateGameInformationService->updateUserInformationForSteamAppId(1));
    }
}
