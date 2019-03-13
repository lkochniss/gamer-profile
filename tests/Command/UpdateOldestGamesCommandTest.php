<?php

namespace tests\App\Command;

use App\Command\UpdateOldestGamesCommand;
use App\Entity\Game;
use App\Repository\GameRepository;
use App\Service\Entity\GameService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class UpdateOldestGamesCommandTest
 */
class UpdateOldestGamesCommandTest extends KernelTestCase
{
    public function testCommandExecuteUpdate(): void
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $game = new Game();
        $game->setSteamAppId(1);

        $gameServiceMock = $this->createMock(GameService::class);
        $gameServiceMock->expects($this->any())
            ->method('update')
            ->with($game->getSteamAppId())
            ->willReturn('U');

        $gameRepositoryMock = $this->createMock(GameRepository::class);
        $gameRepositoryMock->expects($this->any())
            ->method('getLeastUpdatedGames')
            ->with(20)
            ->willReturn([$game]);

        $application = new Application($kernel);
        $application->add(new UpdateOldestGamesCommand(
            $gameServiceMock,
            $gameRepositoryMock
        ));

        $command = $application->find('steam:update:games');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this->assertContains('U', $output);
    }
}
