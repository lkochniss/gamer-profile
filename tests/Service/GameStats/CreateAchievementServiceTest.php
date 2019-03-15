<?php

namespace App\Tests\Service\GameStats;

use App\Entity\Achievement;
use App\Entity\Game;
use App\Entity\JSON\JsonAchievement;
use App\Entity\User;
use App\Repository\AchievementRepository;
use App\Service\GameStats\CreateAchievementService;
use App\Service\Transformation\GameUserInformationService;
use PHPUnit\Framework\TestCase;

class CreateAchievementServiceTest extends TestCase
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var Game
     */
    private $game;

    public function setUp()
    {
        $this->user = new User(1);
        $this->game = new Game(2);
    }

    public function testCreateAchievementsShouldCallAchievementRepository(): void
    {
        $achievementRepositoryMock = $this->createMock(AchievementRepository::class);
        $achievementRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['game' => $this->game, 'user' => $this->user]);

        $gameUserInformationServiceMock = $this->createMock(GameUserInformationService::class);

        $createAchievementsService = new CreateAchievementService($gameUserInformationServiceMock, $achievementRepositoryMock);
        $createAchievementsService->execute($this->user, $this->game);
    }

    public function testCreateAchievementsShouldReturnExistingAchievements(): void
    {
        $expectedAchievement = new Achievement($this->user, $this->game);
        $achievementRepositoryMock = $this->createMock(AchievementRepository::class);
        $achievementRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['game' => $this->game, 'user' => $this->user])
            ->willReturn($expectedAchievement);

        $gameUserInformationServiceMock = $this->createMock(GameUserInformationService::class);

        $createAchievementsService = new CreateAchievementService($gameUserInformationServiceMock, $achievementRepositoryMock);
        $this->assertEquals($expectedAchievement, $createAchievementsService->execute($this->user, $this->game));
    }

    public function testCreateAchievementsShouldCallGameUserInformationService(): void
    {
        $achievementRepositoryMock = $this->createMock(AchievementRepository::class);
        $achievementRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['game' => $this->game, 'user' => $this->user])
            ->willReturn(null);

        $gameUserInformationServiceMock = $this->createMock(GameUserInformationService::class);

        $createAchievementsService = new CreateAchievementService($gameUserInformationServiceMock, $achievementRepositoryMock);
        $createAchievementsService->execute($this->user, $this->game);
    }

    public function testCreateAchievementsShoulPersistAchievement(): void
    {
        $expectedAchievement = new Achievement($this->user, $this->game);
        $expectedAchievement->setPlayerAchievements(0);
        $expectedAchievement->setOverallAchievements(0);

        $achievementRepositoryMock = $this->createMock(AchievementRepository::class);
        $achievementRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['game' => $this->game, 'user' => $this->user])
            ->willReturn(null);

        $gameUserInformationServiceMock = $this->createMock(GameUserInformationService::class);
        $gameUserInformationServiceMock->expects($this->once())
            ->method('getAchievementsForGame')
            ->willReturn(new JsonAchievement());

        $achievementRepositoryMock->expects($this->once())
            ->method('save')
            ->with($expectedAchievement);

        $createAchievementsService = new CreateAchievementService($gameUserInformationServiceMock, $achievementRepositoryMock);
        $this->assertEquals($expectedAchievement, $createAchievementsService->execute($this->user, $this->game));
    }

    public function testCreateAchievementsShouldReturnAchievement(): void
    {
        $expectedAchievement = new Achievement($this->user, $this->game);
        $expectedAchievement->setPlayerAchievements(1);
        $expectedAchievement->setOverallAchievements(2);

        $achievementRepositoryMock = $this->createMock(AchievementRepository::class);
        $achievementRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['game' => $this->game, 'user' => $this->user])
            ->willReturn(null);

        $gameUserInformationServiceMock = $this->createMock(GameUserInformationService::class);
        $gameUserInformationServiceMock->expects($this->once())
            ->method('getAchievementsForGame')
            ->willReturn(new JsonAchievement([
                'playerstats' => [
                    'achievements' => [
                        'achievement-1' => [
                            'achieved' => 1
                        ],
                        'achievement-2' => [
                            'achieved' => 0
                        ],
                    ]
                ]
            ]));

        $createAchievementsService = new CreateAchievementService($gameUserInformationServiceMock, $achievementRepositoryMock);
        $this->assertEquals($expectedAchievement, $createAchievementsService->execute($this->user, $this->game));
    }
}
