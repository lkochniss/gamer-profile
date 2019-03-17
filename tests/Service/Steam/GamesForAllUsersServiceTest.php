<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Steam\GamesForAllUsersService;
use App\Service\Steam\GamesForUserService;
use PHPUnit\Framework\TestCase;

class GamesForAllUsersServiceTest extends TestCase
{
    public function testGamesForAllUsersCreateShouldCallUserRepository(): void
    {
        $userRepositoryMock = $this->createMock(UserRepository::class);
        $userRepositoryMock
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([new User(1)]);

        $createGamesForUserMock = $this->createMock(GamesForUserService::class);

        $createGamesForUsersService = new GamesForAllUsersService($userRepositoryMock, $createGamesForUserMock);
        $createGamesForUsersService->create();
    }

    public function testGamesForAllUsersCreateShouldCallCreateGamesForUserService(): void
    {
        $userRepositoryMock = $this->createMock(UserRepository::class);
        $userRepositoryMock
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([new User(1)]);

        $createGamesForUserMock = $this->createMock(GamesForUserService::class);
        $createGamesForUserMock->expects($this->once())
            ->method('create')
            ->with(1);

        $createGamesForUsersService = new GamesForAllUsersService($userRepositoryMock, $createGamesForUserMock);
        $createGamesForUsersService->create();
    }
}
