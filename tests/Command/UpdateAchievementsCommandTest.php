<?php

namespace tests\App\Command;

use App\Command\UpdateAchievementsCommand;
use App\Entity\Achievement;
use App\Entity\Game;
use App\Entity\User;
use App\Repository\AchievementRepository;
use App\Repository\UserRepository;
use App\Service\Entity\AchievementService;
use App\Service\Transformation\GameUserInformationService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class UpdateAchievementCommandTest
 */
class UpdateAchievementsCommandTest extends KernelTestCase
{
    public function testCommandExecuteUpdate(): void
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $user = new User(1);
        $game = new Game();
        $achievement = new Achievement($user, $game);

        $achievementServiceMock = $this->createMock(AchievementService::class);
        $achievementServiceMock->expects($this->any())
            ->method('update')
            ->with($achievement)
            ->willReturn('U');

        $gameUserInformationServiceMock = $this->createMock(GameUserInformationService::class);
        $gameUserInformationServiceMock->expects($this->any())
            ->method('getRecentlyPlayedGames')
            ->with($user->getSteamId())
            ->willReturn([$game]);

        $userRepositoryMock = $this->createMock(UserRepository::class);
        $userRepositoryMock->expects($this->any())
            ->method('findAll')
            ->willReturn([$user]);

        $achievementRepositoryMock = $this->createMock(AchievementRepository::class);
        $achievementRepositoryMock->expects($this->any())
            ->method('findOneBy')
            ->with(['user' => $user, 'game' => $game])
            ->willReturn($achievement);

        $application = new Application($kernel);
        $application->add(new UpdateAchievementsCommand(
            $achievementServiceMock,
            $gameUserInformationServiceMock,
            $userRepositoryMock,
            $achievementRepositoryMock
        ));

        $command = $application->find('steam:update:achievements');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this->assertContains('U', $output);
    }

    public function testCommandExecuteFail(): void
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $user = new User(1);
        $game = new Game();

        $achievementServiceMock = $this->createMock(AchievementService::class);
        $achievementServiceMock->expects($this->never())
            ->method('update');

        $gameUserInformationServiceMock = $this->createMock(GameUserInformationService::class);
        $gameUserInformationServiceMock->expects($this->any())
            ->method('getRecentlyPlayedGames')
            ->with($user->getSteamId())
            ->willReturn([$game]);

        $userRepositoryMock = $this->createMock(UserRepository::class);
        $userRepositoryMock->expects($this->any())
            ->method('findAll')
            ->willReturn([$user]);

        $achievementRepositoryMock = $this->createMock(AchievementRepository::class);
        $achievementRepositoryMock->expects($this->any())
            ->method('findOneBy')
            ->with(['user' => $user, 'game' => $game])
            ->willReturn(null);

        $application = new Application($kernel);
        $application->add(new UpdateAchievementsCommand(
            $achievementServiceMock,
            $gameUserInformationServiceMock,
            $userRepositoryMock,
            $achievementRepositoryMock
        ));

        $command = $application->find('steam:update:achievements');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this->assertContains('F', $output);
    }
}
