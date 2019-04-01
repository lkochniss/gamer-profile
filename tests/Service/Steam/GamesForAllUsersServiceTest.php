<?php

namespace App\Tests\Service\Steam;

use App\Entity\User;
use App\Service\Security\UserProvider;
use App\Service\Steam\GamesForAllUsersService;
use App\Service\Steam\GamesForUserService;
use PHPUnit\Framework\TestCase;

class GamesForAllUsersServiceTest extends TestCase
{
    public function testGamesForAllUsersCreateShouldCallUserRepository(): void
    {
        $userProvider = $this->createMock(UserProvider::class);
        $userProvider
            ->expects($this->once())
            ->method('loadUsers')
            ->willReturn([new User()]);

        $createGamesForUserMock = $this->createMock(GamesForUserService::class);

        $createGamesForUsersService = new GamesForAllUsersService($userProvider, $createGamesForUserMock);
        $createGamesForUsersService->create();
    }

    public function testGamesForAllUsersCreateShouldCallCreateGamesForUserService(): void
    {
        $userProvider = $this->createMock(UserProvider::class);
        $userProvider
            ->expects($this->once())
            ->method('loadUsers')
            ->willReturn([new User()]);

        $createGamesForUserMock = $this->createMock(GamesForUserService::class);
        $createGamesForUserMock->expects($this->once())
            ->method('create');

        $createGamesForUsersService = new GamesForAllUsersService($userProvider, $createGamesForUserMock);
        $createGamesForUsersService->create();
    }
}
