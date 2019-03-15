<?php

namespace tests\App\Command;

use App\Command\CreateNewGamesCommand;
use App\Entity\Achievement;
use App\Entity\Game;
use App\Entity\GameStats;
use App\Entity\Playtime;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Entity\GameService;
use App\Service\Entity\GameStatsService;
use App\Service\Transformation\GameUserInformationService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class CreateNewGamesCommandTest
 */
class CreateNewGamesCommandTest extends KernelTestCase
{
    public function testCommandExecuteNew(): void
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $user = new User(1);

        $game = new Game(1);

        $gamesArray = [
            [
                'appid' => $game->getSteamAppId(),
            ]
        ];

        $gameUserInformationServiceMock = $this->createMock(GameUserInformationService::class);
        $gameUserInformationServiceMock->expects($this->any())
            ->method('getAllGames')
            ->with($user->getSteamId())
            ->willReturn($gamesArray);

        $gameServiceMock = $this->createMock(GameService::class);
        $gameServiceMock->expects($this->any())
            ->method('createGameIfNotExist')
            ->with($game->getSteamAppId())
            ->willReturn($game);

        $gameStats = new GameStats($user, $game, new Achievement($user, $game), new Playtime($user, $game));

        $gameStatsServiceMock = $this->createMock(GameStatsService::class);
        $gameStatsServiceMock->expects($this->any())
            ->method('createGameStatsIfNotExist')
            ->with($game, $user)
            ->willReturn($gameStats);

        $userRepositoryMock = $this->createMock(UserRepository::class);
        $userRepositoryMock->expects($this->any())
            ->method('findAll')
            ->willReturn([$user]);

        $application = new Application($kernel);
        $application->add(new CreateNewGamesCommand(
            $gameUserInformationServiceMock,
            $gameServiceMock,
            $gameStatsServiceMock,
            $userRepositoryMock
        ));

        $command = $application->find('steam:create:games');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this->assertContains('N', $output);
    }

    public function testCommandExecuteSkipping(): void
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $user = new User(1);

        $game = new Game(1);

        $gamesArray = [
            [
                'appid' => $game->getSteamAppId(),
            ]
        ];

        $gameUserInformationServiceMock = $this->createMock(GameUserInformationService::class);
        $gameUserInformationServiceMock->expects($this->any())
            ->method('getAllGames')
            ->with($user->getSteamId())
            ->willReturn($gamesArray);

        $gameServiceMock = $this->createMock(GameService::class);
        $gameServiceMock->expects($this->any())
            ->method('createGameIfNotExist')
            ->with($game->getSteamAppId())
            ->willReturn($game);

        $gameStatsServiceMock = $this->createMock(GameStatsService::class);
        $gameStatsServiceMock->expects($this->any())
            ->method('createGameStatsIfNotExist')
            ->with($game, $user)
            ->willReturn(null);

        $userRepositoryMock = $this->createMock(UserRepository::class);
        $userRepositoryMock->expects($this->any())
            ->method('findAll')
            ->willReturn([$user]);

        $application = new Application($kernel);
        $application->add(new CreateNewGamesCommand(
            $gameUserInformationServiceMock,
            $gameServiceMock,
            $gameStatsServiceMock,
            $userRepositoryMock
        ));

        $command = $application->find('steam:create:games');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this->assertContains('S', $output);
    }

    public function testCommandExecuteFails(): void
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $user = new User(1);

        $game = new Game(1);

        $gamesArray = [
            [
                'appid' => $game->getSteamAppId(),
            ]
        ];

        $gameUserInformationServiceMock = $this->createMock(GameUserInformationService::class);
        $gameUserInformationServiceMock->expects($this->any())
            ->method('getAllGames')
            ->with($user->getSteamId())
            ->willReturn($gamesArray);

        $gameServiceMock = $this->createMock(GameService::class);
        $gameServiceMock->expects($this->any())
            ->method('createGameIfNotExist')
            ->with($game->getSteamAppId())
            ->willReturn(null);

        $gameStatsServiceMock = $this->createMock(GameStatsService::class);
        $gameStatsServiceMock->expects($this->never())
            ->method('createGameStatsIfNotExist');

        $userRepositoryMock = $this->createMock(UserRepository::class);
        $userRepositoryMock->expects($this->any())
            ->method('findAll')
            ->willReturn([$user]);

        $application = new Application($kernel);
        $application->add(new CreateNewGamesCommand(
            $gameUserInformationServiceMock,
            $gameServiceMock,
            $gameStatsServiceMock,
            $userRepositoryMock
        ));

        $command = $application->find('steam:create:games');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this->assertContains('F', $output);
    }
}
