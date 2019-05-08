<?php

namespace tests\App\Service\Api;

use App\Entity\Game;
use App\Entity\GameSession;
use App\Entity\PlaytimePerMonth;
use App\Entity\User;
use App\Repository\GameRepository;
use App\Repository\GameSessionRepository;
use App\Service\Api\GameSessionApiService;
use App\Service\Util\TimeConverterUtil;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class GameSessionApiServiceTest
 */
class GameSessionApiServiceTest extends TestCase
{
    public function testGetSessionsLastDaysShouldCallRepository(): void
    {
        $user = new User();

        $sessionRepositoryMock = $this->createMock(GameSessionRepository::class);
        $sessionRepositoryMock->expects($this->once())
            ->method('findForLastDays')
            ->with($user)
            ->willReturn([]);

        $gameRepositoryMock = $this->createMock(GameRepository::class);
        $timeUtilMock = $this->createMock(TimeConverterUtil::class);

        $service = new GameSessionApiService(
            $sessionRepositoryMock,
            $gameRepositoryMock,
            $timeUtilMock
        );

        $service->getSessionsLastDays($user);
    }

    public function testGetSessionsLastDaysShouldCallUtil(): void
    {
        $user = new User();
        $user->setSteamId(1);

        $game = new Game(2);
        $session = new GameSession($game, $user->getSteamId());
        $session->setCreatedAt();
        $session->setDuration(20);

        $sessionRepositoryMock = $this->createMock(GameSessionRepository::class);
        $sessionRepositoryMock->expects($this->once())
            ->method('findForLastDays')
            ->with($user)
            ->willReturn([$session]);

        $gameRepositoryMock = $this->createMock(GameRepository::class);
        $timeUtilMock = $this->createMock(TimeConverterUtil::class);
        $timeUtilMock->expects($this->once())
            ->method('convertRecentTime')
            ->with($session->getDuration());

        $service = new GameSessionApiService(
            $sessionRepositoryMock,
            $gameRepositoryMock,
            $timeUtilMock
        );

        $service->getSessionsLastDays($user);
    }

    public function testGetSessionsLastDaysShouldReturnJsonResponse(): void
    {
        $user = new User();
        $user->setSteamId(1);

        $game = new Game(2);
        $session = new GameSession($game, $user->getSteamId());
        $session->setCreatedAt();
        $session->setDuration(20);

        $sessionRepositoryMock = $this->createMock(GameSessionRepository::class);
        $sessionRepositoryMock->expects($this->once())
            ->method('findForLastDays')
            ->with($user)
            ->willReturn([
                $session,
                $session,
                $session
            ]);

        $gameRepositoryMock = $this->createMock(GameRepository::class);
        $timeUtilMock = $this->createMock(TimeConverterUtil::class);
        $timeUtilMock->expects($this->any())
            ->method('convertRecentTime')
            ->willReturn($session->getDuration());

        $service = new GameSessionApiService(
            $sessionRepositoryMock,
            $gameRepositoryMock,
            $timeUtilMock
        );

        $expectedReturn =  [
            [
                'date' => $session->getDate()->format('d M Y'),
                'timeInMinutes' => $session->getDuration() * 3,
                'timeForTooltip' => strval($session->getDuration())
            ]
        ];

        $this->assertEquals(new JsonResponse($expectedReturn), $service->getSessionsLastDays($user));
    }

    public function testGetSessionsThisYearShouldCallRepository(): void
    {
        $user = new User();

        $sessionRepositoryMock = $this->createMock(GameSessionRepository::class);
        $sessionRepositoryMock->expects($this->once())
            ->method('findForThisYear')
            ->with($user)
            ->willReturn([]);

        $gameRepositoryMock = $this->createMock(GameRepository::class);
        $timeUtilMock = $this->createMock(TimeConverterUtil::class);

        $service = new GameSessionApiService(
            $sessionRepositoryMock,
            $gameRepositoryMock,
            $timeUtilMock
        );

        $service->getSessionsThisYear($user);
    }

    public function testGetSessionsThisYearShouldCallUtil(): void
    {
        $user = new User();
        $user->setSteamId(1);

        $game = new Game(2);
        $session = new GameSession($game, $user->getSteamId());
        $session->setCreatedAt();
        $session->setDuration(20);

        $sessionRepositoryMock = $this->createMock(GameSessionRepository::class);
        $sessionRepositoryMock->expects($this->once())
            ->method('findForThisYear')
            ->with($user)
            ->willReturn([$session]);

        $gameRepositoryMock = $this->createMock(GameRepository::class);
        $timeUtilMock = $this->createMock(TimeConverterUtil::class);
        $timeUtilMock->expects($this->once())
            ->method('convertRecentTime')
            ->with($session->getDuration());

        $service = new GameSessionApiService(
            $sessionRepositoryMock,
            $gameRepositoryMock,
            $timeUtilMock
        );

        $service->getSessionsThisYear($user);
    }

    public function testGetSessionsThisYearShouldReturnJsonResponse(): void
    {
        $user = new User();
        $user->setSteamId(1);

        $game = new Game(2);
        $session = new GameSession($game, $user->getSteamId());
        $session->setCreatedAt();
        $session->setDuration(20);

        $sessionRepositoryMock = $this->createMock(GameSessionRepository::class);
        $sessionRepositoryMock->expects($this->once())
            ->method('findForThisYear')
            ->with($user)
            ->willReturn([
                $session,
                $session,
                $session
            ]);

        $gameRepositoryMock = $this->createMock(GameRepository::class);
        $timeUtilMock = $this->createMock(TimeConverterUtil::class);
        $timeUtilMock->expects($this->any())
            ->method('convertRecentTime')
            ->willReturn($session->getDuration());

        $service = new GameSessionApiService(
            $sessionRepositoryMock,
            $gameRepositoryMock,
            $timeUtilMock
        );

        $expectedReturn =  [
            [
                'date' => $session->getDate()->format('d M Y'),
                'timeInMinutes' => $session->getDuration() * 3,
                'timeForTooltip' => strval($session->getDuration())
            ]
        ];

        $this->assertEquals(new JsonResponse($expectedReturn), $service->getSessionsThisYear($user));
    }

    public function testGetSessionsForYearShouldCallRepository(): void
    {
        $user = new User();

        $sessionRepositoryMock = $this->createMock(GameSessionRepository::class);
        $sessionRepositoryMock->expects($this->once())
            ->method('findForYear')
            ->with(2019, $user)
            ->willReturn([]);

        $gameRepositoryMock = $this->createMock(GameRepository::class);
        $timeUtilMock = $this->createMock(TimeConverterUtil::class);

        $service = new GameSessionApiService(
            $sessionRepositoryMock,
            $gameRepositoryMock,
            $timeUtilMock
        );

        $service->getSessionsForYear(2019, $user);
    }

    public function testGetSessionsForYearShouldCallUtil(): void
    {
        $user = new User();
        $user->setSteamId(1);

        $game = new Game(2);
        $session = new GameSession($game, $user->getSteamId());
        $session->setCreatedAt();
        $session->setDuration(20);

        $sessionRepositoryMock = $this->createMock(GameSessionRepository::class);
        $sessionRepositoryMock->expects($this->once())
            ->method('findForYear')
            ->with(2019, $user)
            ->willReturn([$session]);

        $gameRepositoryMock = $this->createMock(GameRepository::class);
        $timeUtilMock = $this->createMock(TimeConverterUtil::class);
        $timeUtilMock->expects($this->once())
            ->method('convertRecentTime')
            ->with($session->getDuration());

        $service = new GameSessionApiService(
            $sessionRepositoryMock,
            $gameRepositoryMock,
            $timeUtilMock
        );

        $service->getSessionsForYear(2019, $user);
    }

    public function testGetSessionsForYearShouldReturnJsonResponse(): void
    {
        $user = new User();
        $user->setSteamId(1);

        $game = new Game(2);
        $session = new GameSession($game, $user->getSteamId());
        $session->setCreatedAt();
        $session->setDuration(20);

        $sessionRepositoryMock = $this->createMock(GameSessionRepository::class);
        $sessionRepositoryMock->expects($this->once())
            ->method('findForYear')
            ->with(2019, $user)
            ->willReturn([
                $session,
                $session,
                $session
            ]);

        $gameRepositoryMock = $this->createMock(GameRepository::class);
        $timeUtilMock = $this->createMock(TimeConverterUtil::class);
        $timeUtilMock->expects($this->any())
            ->method('convertRecentTime')
            ->willReturn($session->getDuration());

        $service = new GameSessionApiService(
            $sessionRepositoryMock,
            $gameRepositoryMock,
            $timeUtilMock
        );

        $expectedReturn =  [
            [
                'date' => $session->getDate()->format('d M Y'),
                'timeInMinutes' => $session->getDuration() * 3,
                'timeForTooltip' => strval($session->getDuration())
            ]
        ];

        $this->assertEquals(new JsonResponse($expectedReturn), $service->getSessionsForYear(2019, $user));
    }

    public function testGetSessionsForGameShouldCallGameRepository(): void
    {
        $user = new User();
        $user->setSteamId(1);

        $gameId = 2;

        $sessionRepositoryMock = $this->createMock(GameSessionRepository::class);

        $gameRepositoryMock = $this->createMock(GameRepository::class);
        $gameRepositoryMock->expects($this->once())
            ->method('find')
            ->with($gameId);

        $timeUtilMock = $this->createMock(TimeConverterUtil::class);

        $service = new GameSessionApiService(
            $sessionRepositoryMock,
            $gameRepositoryMock,
            $timeUtilMock
        );

        $service->getSessionsForGame($gameId, $user);
    }

    public function testGetSessionsForGameShouldReturnEmptyJsonOnNullGame(): void
    {
        $user = new User();
        $user->setSteamId(1);

        $gameId = 2;

        $sessionRepositoryMock = $this->createMock(GameSessionRepository::class);

        $gameRepositoryMock = $this->createMock(GameRepository::class);
        $gameRepositoryMock->expects($this->once())
            ->method('find')
            ->with($gameId);

        $timeUtilMock = $this->createMock(TimeConverterUtil::class);

        $service = new GameSessionApiService(
            $sessionRepositoryMock,
            $gameRepositoryMock,
            $timeUtilMock
        );

        $this->assertEquals(new JsonResponse([]), $service->getSessionsForGame($gameId, $user));
    }

    public function testGetSessionsForGameShouldCallSessionRepository(): void
    {
        $user = new User();
        $user->setSteamId(1);

        $gameId = 2;
        $game = new Game(3);

        $sessionRepositoryMock = $this->createMock(GameSessionRepository::class);
        $sessionRepositoryMock->expects($this->once())
            ->method('findBy')
            ->with([
                'game' => $game,
                'steamUserId' => $user->getSteamId()
            ])
            ->willReturn([]);

        $gameRepositoryMock = $this->createMock(GameRepository::class);
        $gameRepositoryMock->expects($this->once())
            ->method('find')
            ->with($gameId)
            ->willReturn($game);

        $timeUtilMock = $this->createMock(TimeConverterUtil::class);

        $service = new GameSessionApiService(
            $sessionRepositoryMock,
            $gameRepositoryMock,
            $timeUtilMock
        );

        $service->getSessionsForGame($gameId, $user);
    }


    public function testGetSessionsForGameShouldReturnJsonResponse(): void
    {
        $user = new User();
        $user->setSteamId(1);

        $gameId = 2;
        $game = new Game(3);

        $session = new GameSession($game, $user->getSteamId());
        $session->setCreatedAt();
        $session->setDuration(20);

        $sessionRepositoryMock = $this->createMock(GameSessionRepository::class);
        $sessionRepositoryMock->expects($this->once())
            ->method('findBy')
            ->with([
                'game' => $game,
                'steamUserId' => $user->getSteamId()
            ])
            ->willReturn([$session]);

        $gameRepositoryMock = $this->createMock(GameRepository::class);
        $gameRepositoryMock->expects($this->once())
            ->method('find')
            ->with($gameId)
            ->willReturn($game);

        $timeUtilMock = $this->createMock(TimeConverterUtil::class);

        $service = new GameSessionApiService(
            $sessionRepositoryMock,
            $gameRepositoryMock,
            $timeUtilMock
        );

        $expectedReturn =  [
            [
                'date' => $session->getDate()->format('d M Y'),
                'timeInMinutes' => $session->getDuration(),
                'timeForTooltip' => ""
            ]
        ];

        $this->assertEquals(new JsonResponse($expectedReturn), $service->getSessionsForGame($gameId, $user));
    }
}
