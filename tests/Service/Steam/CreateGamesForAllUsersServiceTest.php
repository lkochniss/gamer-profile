<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Steam\CreateGamesForAllUsersService;
use App\Service\Steam\CreateGamesForUserService;
use PHPUnit\Framework\TestCase;

class CreateGamesForAllUsersServiceTest extends TestCase
{
    public function testCreateGamesForAllUsersShouldCallUserRepository(): void
    {
        $userRepositoryMock = $this->createMock(UserRepository::class);
        $userRepositoryMock
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([new User(1)]);

        $createGamesForUserMock = $this->createMock(CreateGamesForUserService::class);

        $createGamesForUsersService = new CreateGamesForAllUsersService($userRepositoryMock, $createGamesForUserMock);
        $createGamesForUsersService->execute();
    }

    public function testCreateGamesForAllUsersShouldCallCreateGamesForUserService(): void
    {
        $userRepositoryMock = $this->createMock(UserRepository::class);
        $userRepositoryMock
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([new User(1)]);

        $createGamesForUserMock = $this->createMock(CreateGamesForUserService::class);
        $createGamesForUserMock->expects($this->once())
            ->method('execute')
            ->with(1);

        $createGamesForUsersService = new CreateGamesForAllUsersService($userRepositoryMock, $createGamesForUserMock);
        $createGamesForUsersService->execute();
    }
}
