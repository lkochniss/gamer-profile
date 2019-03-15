<?php

namespace tests\App\Command;

use App\Command\UpdatePlaytimesCommand;
use App\Entity\Game;
use App\Entity\Playtime;
use App\Entity\User;
use App\Repository\GameRepository;
use App\Repository\PlaytimeRepository;
use App\Repository\UserRepository;
use App\Service\Entity\PlaytimeService;
use App\Service\Transformation\GameUserInformationService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class UpdatePlaytimeCommandTest
 */
class UpdatePlaytimesCommandTest extends KernelTestCase
{
    public function testCommandExecuteUpdate(): void
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $user = new User(1);
        $game = new Game(1);
        $playtime = new Playtime($user, $game);

        $playtimeServiceMock = $this->createMock(PlaytimeService::class);
        $playtimeServiceMock->expects($this->any())
            ->method('update')
            ->with($playtime)
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

        $playtimeRepositoryMock = $this->createMock(PlaytimeRepository::class);
        $playtimeRepositoryMock->expects($this->any())
            ->method('getRecentPlaytime')
            ->with($user)
            ->willReturn([$playtime]);

        $playtimeRepositoryMock->expects($this->any())
            ->method('findOneBy')
            ->with(['user' => $user, 'game' => $game])
            ->willReturn($playtime);

        $gameRepositoryMock = $this->createMock(GameRepository::class);

        $application = new Application($kernel);
        $application->add(new UpdatePlaytimesCommand(
            $playtimeServiceMock,
            $gameUserInformationServiceMock,
            $userRepositoryMock,
            $playtimeRepositoryMock,
            $gameRepositoryMock
        ));

        $command = $application->find('steam:update:playtime');
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
        $game = new Game(1);

        $playtimeServiceMock = $this->createMock(PlaytimeService::class);
        $playtimeServiceMock->expects($this->never())
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

        $playtimeRepositoryMock = $this->createMock(PlaytimeRepository::class);
        $playtimeRepositoryMock->expects($this->any())
            ->method('getRecentPlaytime')
            ->with($user)
            ->willReturn([]);

        $playtimeRepositoryMock->expects($this->any())
            ->method('findOneBy')
            ->with(['user' => $user, 'game' => $game])
            ->willReturn(null);

        $gameRepositoryMock = $this->createMock(GameRepository::class);

        $application = new Application($kernel);
        $application->add(new UpdatePlaytimesCommand(
            $playtimeServiceMock,
            $gameUserInformationServiceMock,
            $userRepositoryMock,
            $playtimeRepositoryMock,
            $gameRepositoryMock
        ));

        $command = $application->find('steam:update:playtime');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this->assertContains('F', $output);
    }
}
