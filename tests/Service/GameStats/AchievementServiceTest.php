<?php

namespace App\Tests\Service\GameStats;

use App\Entity\Achievement;
use App\Entity\Game;
use App\Entity\JSON\JsonAchievement;
use App\Entity\User;
use App\Repository\AchievementRepository;
use App\Service\GameStats\AchievementService;
use App\Service\Transformation\GameUserInformationService;
use PHPUnit\Framework\TestCase;

class AchievementServiceTest extends TestCase
{
    /**
     * @var string
     */
    private $steamUserId;

    /**
     * @var Game
     */
    private $game;

    public function setUp()
    {
        $this->steamUserId = 1;
        $this->game = new Game(2);
    }

    public function testAchievementCreateShouldCallAchievementRepository(): void
    {
        $achievementRepositoryMock = $this->createMock(AchievementRepository::class);
        $achievementRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['game' => $this->game, 'steamUserId' => $this->steamUserId]);

        $gameUserInformationServiceMock = $this->createMock(GameUserInformationService::class);

        $createAchievementsService = new AchievementService(
            $gameUserInformationServiceMock,
            $achievementRepositoryMock
        );
        $createAchievementsService->create($this->steamUserId, $this->game);
    }

    public function testAchievementCreateShouldReturnExistingAchievements(): void
    {
        $expectedAchievement = new Achievement($this->steamUserId, $this->game);
        $achievementRepositoryMock = $this->createMock(AchievementRepository::class);
        $achievementRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['game' => $this->game, 'steamUserId' => $this->steamUserId])
            ->willReturn($expectedAchievement);

        $gameUserInformationServiceMock = $this->createMock(GameUserInformationService::class);

        $createAchievementsService = new AchievementService(
            $gameUserInformationServiceMock,
            $achievementRepositoryMock
        );
        $this->assertEquals($expectedAchievement, $createAchievementsService->create($this->steamUserId, $this->game));
    }

    public function testAchievementCreateShouldCallGameUserInformationService(): void
    {
        $achievementRepositoryMock = $this->createMock(AchievementRepository::class);
        $achievementRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['game' => $this->game, 'steamUserId' => $this->steamUserId])
            ->willReturn(null);

        $gameUserInformationServiceMock = $this->createMock(GameUserInformationService::class);

        $createAchievementsService = new AchievementService(
            $gameUserInformationServiceMock,
            $achievementRepositoryMock
        );
        $createAchievementsService->create($this->steamUserId, $this->game);
    }

    public function testAchievementCreateShouldPersistAchievement(): void
    {
        $expectedAchievement = new Achievement($this->steamUserId, $this->game);
        $expectedAchievement->setPlayerAchievements(0);
        $expectedAchievement->setOverallAchievements(0);

        $achievementRepositoryMock = $this->createMock(AchievementRepository::class);
        $achievementRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['game' => $this->game, 'steamUserId' => $this->steamUserId])
            ->willReturn(null);

        $gameUserInformationServiceMock = $this->createMock(GameUserInformationService::class);
        $gameUserInformationServiceMock->expects($this->once())
            ->method('getAchievementsForGame')
            ->willReturn(new JsonAchievement());

        $achievementRepositoryMock->expects($this->once())
            ->method('save')
            ->with($expectedAchievement);

        $createAchievementsService = new AchievementService(
            $gameUserInformationServiceMock,
            $achievementRepositoryMock
        );
        $createAchievementsService->create($this->steamUserId, $this->game);
    }

    public function testAchievementCreateShouldReturnAchievement(): void
    {
        $expectedAchievement = new Achievement($this->steamUserId, $this->game);
        $expectedAchievement->setPlayerAchievements(1);
        $expectedAchievement->setOverallAchievements(2);

        $achievementRepositoryMock = $this->createMock(AchievementRepository::class);
        $achievementRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['game' => $this->game, 'steamUserId' => $this->steamUserId])
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

        $createAchievementsService = new AchievementService(
            $gameUserInformationServiceMock,
            $achievementRepositoryMock
        );
        $this->assertEquals($expectedAchievement, $createAchievementsService->create($this->steamUserId, $this->game));
    }

    public function testAchievementUpdateShouldPersistAchievement(): void
    {
        $expectedAchievement = new Achievement($this->steamUserId, $this->game);
        $expectedAchievement->setPlayerAchievements(0);
        $expectedAchievement->setOverallAchievements(0);

        $achievementRepositoryMock = $this->createMock(AchievementRepository::class);

        $gameUserInformationServiceMock = $this->createMock(GameUserInformationService::class);
        $gameUserInformationServiceMock->expects($this->once())
            ->method('getAchievementsForGame')
            ->willReturn(new JsonAchievement());

        $achievementRepositoryMock->expects($this->once())
            ->method('save')
            ->with($expectedAchievement);

        $createAchievementsService = new AchievementService(
            $gameUserInformationServiceMock,
            $achievementRepositoryMock
        );
        $createAchievementsService->update(new Achievement($this->steamUserId, $this->game));
    }

    public function testAchievementUpdateShouldReturnAchievement(): void
    {
        $expectedAchievement = new Achievement($this->steamUserId, $this->game);
        $expectedAchievement->setPlayerAchievements(1);
        $expectedAchievement->setOverallAchievements(2);

        $achievementRepositoryMock = $this->createMock(AchievementRepository::class);

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

        $createAchievementsService = new AchievementService(
            $gameUserInformationServiceMock,
            $achievementRepositoryMock
        );
        $this->assertEquals(
            $expectedAchievement,
            $createAchievementsService->update(new Achievement($this->steamUserId, $this->game))
        );
    }
}
