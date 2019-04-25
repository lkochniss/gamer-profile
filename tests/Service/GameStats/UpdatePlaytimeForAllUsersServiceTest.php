<?php

namespace App\Tests\Service\GameStats;

use App\Entity\User;
use App\Service\GameStats\UpdatePlaytimeForAllUsersService;
use App\Service\GameStats\UpdatePlaytimeForUserService;
use App\Service\Security\UserProvider;
use PHPUnit\Framework\TestCase;

class UpdatePlaytimeForAllUsersServiceTest extends TestCase
{
    public function testExecuteShouldCallUserProvider(): void
    {
        $serviceMock =  $this->createMock(UpdatePlaytimeForUserService::class);
        $providerMock = $this->createMock(UserProvider::class);
        $providerMock->expects($this->once())
            ->method('loadUsers');

        $service = new UpdatePlaytimeForAllUsersService($serviceMock, $providerMock);
        $service->execute();
    }

    public function testExecuteShouldCallService(): void
    {
        $serviceMock =  $this->createMock(UpdatePlaytimeForUserService::class);
        $serviceMock->expects($this->once())#
            ->method('execute')
            ->with(new User());

        $providerMock = $this->createMock(UserProvider::class);
        $providerMock->expects($this->once())
            ->method('loadUsers')
            ->willReturn([new User()]);

        $service = new UpdatePlaytimeForAllUsersService($serviceMock, $providerMock);
        $service->execute();
    }
}
