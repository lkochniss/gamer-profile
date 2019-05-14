<?php

namespace tests\App\Service\Api;

use App\Entity\PlaytimePerMonth;
use App\Entity\User;
use App\Repository\PlaytimePerMonthRepository;
use App\Service\Api\PlaytimePerMonthApiService;
use App\Service\Transformation\PlaytimePerMonthTransformation;
use PHPUnit\Framework\TestCase;

/**
 * Class PlaytimePerMonthApiServiceTest
 */
class PlaytimePerMonthApiServiceTest extends TestCase
{
    public function testGetSessionsPerMonthShouldFindEntities(): void
    {
        $user = new User();
        $user->setSteamId(1);

        $serviceMock = $this->createMock(PlaytimePerMonthTransformation::class);
        $repositoryMock = $this->createMock(PlaytimePerMonthRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findBy')
            ->with([
                'steamUserId' => $user->getSteamId()
            ])
            ->willReturn([]);

        $service = new PlaytimePerMonthApiService($serviceMock, $repositoryMock);
        $service->getSessionsPerMonth($user);
    }

    public function testGetSessionsPerMonthShouldCallPlaytimeService(): void
    {
        $user = new User();
        $user->setSteamId(1);
        $playtimePerMonth = new PlaytimePerMonth(new \DateTime(), $user->getSteamId());

        $serviceMock = $this->createMock(PlaytimePerMonthTransformation::class);
        $serviceMock->expects($this->once())
            ->method('getPlaytimeResponse')
            ->with($playtimePerMonth);

        $repositoryMock = $this->createMock(PlaytimePerMonthRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findBy')
            ->with([
                'steamUserId' => $user->getSteamId()
            ])
            ->willReturn([$playtimePerMonth]);

        $service = new PlaytimePerMonthApiService($serviceMock, $repositoryMock);
        $service->getSessionsPerMonth($user);
    }

    public function testGetAveragePlaytimePerMonthShouldFindEntities(): void
    {
        $user = new User();
        $user->setSteamId(1);

        $serviceMock = $this->createMock(PlaytimePerMonthTransformation::class);
        $repositoryMock = $this->createMock(PlaytimePerMonthRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findBy')
            ->with([
                'steamUserId' => $user->getSteamId()
            ])
            ->willReturn([]);

        $service = new PlaytimePerMonthApiService($serviceMock, $repositoryMock);
        $service->getAveragePlaytimePerMonth($user);
    }

    public function testGetAveragePlaytimePerMonthShouldCallPlaytimeService(): void
    {
        $user = new User();
        $user->setSteamId(1);
        $playtimePerMonth = new PlaytimePerMonth(new \DateTime(), $user->getSteamId());

        $serviceMock = $this->createMock(PlaytimePerMonthTransformation::class);
        $serviceMock->expects($this->once())
            ->method('getAveragePlaytimeResponse')
            ->with($playtimePerMonth);

        $repositoryMock = $this->createMock(PlaytimePerMonthRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findBy')
            ->with([
                'steamUserId' => $user->getSteamId()
            ])
            ->willReturn([$playtimePerMonth]);

        $service = new PlaytimePerMonthApiService($serviceMock, $repositoryMock);
        $service->getAveragePlaytimePerMonth($user);
    }
}
