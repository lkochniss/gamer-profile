<?php

namespace App\Tests\Service\GameStats;

use App\Entity\Game;
use App\Entity\User;
use App\Repository\AchievementRepository;
use App\Service\GameStats\CreateAchievementService;
use App\Service\Transformation\GameUserInformationService;
use PHPUnit\Framework\TestCase;

class CreateAchievementsServiceTest extends TestCase
{
    public function testCreateAchievementsShouldCallAchievementRepository(): void
    {
        $achievementRepositoryMock = $this->createMock(AchievementRepository::class);
        $achievementRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['game' => new Game(2), 'user' => new User(1)]);

        $gameUserInformationServiceMock = $this->createMock(GameUserInformationService::class);

        $createAchievementsService = new CreateAchievementService($gameUserInformationServiceMock, $achievementRepositoryMock);
        $createAchievementsService->execute(new User(1), new Game(2));
    }
}
