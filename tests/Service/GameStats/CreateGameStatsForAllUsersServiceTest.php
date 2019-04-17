<?php

namespace App\Tests\Service\GameStats;

use App\Entity\User;
use App\Service\GameStats\CreateGameStatsForAllUsersService;
use App\Service\GameStats\CreateGameStatsForUsersGamesService;
use App\Service\Security\UserProvider;
use PHPUnit\Framework\TestCase;

class CreateGameStatsForAllUsersServiceTest extends TestCase
{
    public function testExecuteShouldCallUserProvider(): void
    {
        $providerMock = $this->createMock(UserProvider::class);
        $providerMock->expects($this->once())
            ->method('loadUsers')
            ->willReturn([]);

        $serviceMock = $this->createMock(CreateGameStatsForUsersGamesService::class);

        $service = new CreateGameStatsForAllUsersService($serviceMock, $providerMock);
        $service->execute();
    }

    public function testExecuteShouldCallService(): void
    {
        $providerMock = $this->createMock(UserProvider::class);
        $providerMock->expects($this->once())
            ->method('loadUsers')
            ->willReturn([new User()]);

        $serviceMock = $this->createMock(CreateGameStatsForUsersGamesService::class);
        $serviceMock->expects($this->once())
            ->method('execute')
            ->with(new User());

        $service = new CreateGameStatsForAllUsersService($serviceMock, $providerMock);
        $service->execute();
    }
}
