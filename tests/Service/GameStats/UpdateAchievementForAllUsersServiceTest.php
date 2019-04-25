<?php

namespace App\Tests\Service\GameStats;

use App\Entity\User;
use App\Service\GameStats\UpdateAchievementForAllUsersService;
use App\Service\GameStats\UpdateAchievementForUserService;
use App\Service\Security\UserProvider;
use PHPUnit\Framework\TestCase;

class UpdateAchievementForAllUsersServiceTest extends TestCase
{
    public function testRecentlyShouldCallLoadUsers(): void
    {
        $serviceMock = $this->createMock(UpdateAchievementForUserService::class);
        $providerMock = $this->createMock(UserProvider::class);
        $providerMock->expects($this->once())
            ->method('loadUsers');

        $service = new UpdateAchievementForAllUsersService($serviceMock, $providerMock);
        $service->recently();
    }

    public function testRecentlyShouldCallService(): void
    {
        $serviceMock = $this->createMock(UpdateAchievementForUserService::class);
        $serviceMock->expects($this->once())
            ->method('recently')
            ->with(new User());

        $providerMock = $this->createMock(UserProvider::class);
        $providerMock->expects($this->once())
            ->method('loadUsers')
            ->willReturn([new User()]);

        $service = new UpdateAchievementForAllUsersService($serviceMock, $providerMock);
        $service->recently();
    }

    public function testNoneExistingShouldCallLoadUsers(): void
    {
        $serviceMock = $this->createMock(UpdateAchievementForUserService::class);
        $providerMock = $this->createMock(UserProvider::class);
        $providerMock->expects($this->once())
            ->method('loadUsers');

        $service = new UpdateAchievementForAllUsersService($serviceMock, $providerMock);
        $service->noneExisting();
    }

    public function testNoneExistingShouldCallService(): void
    {
        $serviceMock = $this->createMock(UpdateAchievementForUserService::class);
        $serviceMock->expects($this->once())
            ->method('noneExisting')
            ->with(new User());

        $providerMock = $this->createMock(UserProvider::class);
        $providerMock->expects($this->once())
            ->method('loadUsers')
            ->willReturn([new User()]);

        $service = new UpdateAchievementForAllUsersService($serviceMock, $providerMock);
        $service->noneExisting();
    }
}
