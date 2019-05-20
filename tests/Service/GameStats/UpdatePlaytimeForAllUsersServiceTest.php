<?php

namespace App\Tests\Service\GameStats;

use App\Entity\User;
use App\Service\GameStats\UpdateGameStatusForUserService;
use App\Service\GameStats\UpdatePlaytimeForAllUsersService;
use App\Service\GameStats\UpdatePlaytimeForUserService;
use App\Service\Security\UserProvider;
use PHPUnit\Framework\TestCase;

class UpdatePlaytimeForAllUsersServiceTest extends TestCase
{
    public function testExecuteShouldCallUserProvider(): void
    {
        $playtimeServiceMock =  $this->createMock(UpdatePlaytimeForUserService::class);
        $gameStatusServiceMock = $this->createMock(UpdateGameStatusForUserService::class);
        $providerMock = $this->createMock(UserProvider::class);
        $providerMock->expects($this->once())
            ->method('loadUsers');

        $service = new UpdatePlaytimeForAllUsersService($playtimeServiceMock, $gameStatusServiceMock, $providerMock);
        $service->execute();
    }

    public function testExecuteShouldCallPlaytimeService(): void
    {
        $playtimeServiceMock =  $this->createMock(UpdatePlaytimeForUserService::class);
        $playtimeServiceMock->expects($this->once())
            ->method('execute')
            ->with(new User());

        $gameStatusServiceMock = $this->createMock(UpdateGameStatusForUserService::class);

        $providerMock = $this->createMock(UserProvider::class);
        $providerMock->expects($this->once())
            ->method('loadUsers')
            ->willReturn([new User()]);

        $service = new UpdatePlaytimeForAllUsersService($playtimeServiceMock, $gameStatusServiceMock, $providerMock);
        $service->execute();
    }

    public function testExecuteShouldCallGameStatusServiceSetStatusPlaying(): void
    {
        $playtimeServiceMock =  $this->createMock(UpdatePlaytimeForUserService::class);
        $playtimeServiceMock->expects($this->once())
            ->method('execute')
            ->with(new User());

        $gameStatusServiceMock = $this->createMock(UpdateGameStatusForUserService::class);
        $gameStatusServiceMock->expects($this->once())
            ->method('setStatusPlayingForRecentPlayed')
            ->with(new User());

        $providerMock = $this->createMock(UserProvider::class);
        $providerMock->expects($this->once())
            ->method('loadUsers')
            ->willReturn([new User()]);

        $service = new UpdatePlaytimeForAllUsersService($playtimeServiceMock, $gameStatusServiceMock, $providerMock);
        $service->execute();
    }

    public function testExecuteShouldCallGameStatusServiceSetStatusPaused(): void
    {
        $playtimeServiceMock =  $this->createMock(UpdatePlaytimeForUserService::class);
        $playtimeServiceMock->expects($this->once())
            ->method('execute')
            ->with(new User());

        $gameStatusServiceMock = $this->createMock(UpdateGameStatusForUserService::class);
        $gameStatusServiceMock->expects($this->once())
            ->method('setStatusPausedForPlayingGamesWithoutRecentPlayed')
            ->with(new User());

        $providerMock = $this->createMock(UserProvider::class);
        $providerMock->expects($this->once())
            ->method('loadUsers')
            ->willReturn([new User()]);

        $service = new UpdatePlaytimeForAllUsersService($playtimeServiceMock, $gameStatusServiceMock, $providerMock);
        $service->execute();
    }
}
