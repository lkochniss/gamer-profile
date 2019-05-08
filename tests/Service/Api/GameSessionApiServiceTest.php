<?php

namespace tests\App\Service\Api;

use App\Entity\Game;
use App\Entity\GameSession;
use App\Entity\PlaytimePerMonth;
use App\Entity\User;
use App\Repository\GameRepository;
use App\Repository\GameSessionRepository;
use App\Repository\PlaytimePerMonthRepository;
use App\Service\Api\GameSessionApiService;
use App\Service\Api\PlaytimePerMonthApiService;
use App\Service\Transformation\PlaytimePerMonthTransformation;
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
}
