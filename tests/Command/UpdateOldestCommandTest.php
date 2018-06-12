<?php

namespace tests\App\Command;

use App\Command\UpdateOldestGamesCommand;
use App\Entity\Game;
use App\Repository\GameRepository;
use App\Service\Entity\UpdateGameInformationService;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class UpdateOldestCommandTest
 */
class UpdateOldestCommandTest extends KernelTestCase
{

    /**
     * @var Command
     */
    private $command;

    /**
     * @var Application
     */
    private $application;

    /**
     * @var MockObject
     */
    private $updateGameInformationServiceMock;

    /**
     * @var MockObject
     */
    private $gameRepositoryMock;

    public function setUp(): void
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $this->updateGameInformationServiceMock = $this->createMock(UpdateGameInformationService::class);
        $this->gameRepositoryMock = $this->createMock(GameRepository::class);
        $this->application = new Application($kernel);
    }

    public function testCommandExecute(): void
    {
        $this->setGameUserInformationServiceMock();
        $this->setGameRepositoryMock();
        $this->addCommandToKernel();

        $this->command = $this->application->find('steam:update:oldest');
        $commandTester = new CommandTester($this->command);
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this->assertContains('U', $output);
    }

    private function addCommandToKernel(): void
    {
        $this->application->add(new UpdateOldestGamesCommand(
            $this->updateGameInformationServiceMock,
            $this->gameRepositoryMock
        ));
    }

    private function setGameUserInformationServiceMock():void
    {
        $this->updateGameInformationServiceMock->expects($this->any())
            ->method('updateGameInformationForSteamAppId')
            ->with(1)
            ->willReturn('U');
    }

    private function setGameRepositoryMock():void
    {
        $game = new Game();
        $game->setSteamAppId(1);
        $this->gameRepositoryMock->expects($this->any())
            ->method('getLeastUpdatedGames')
            ->with(20)
            ->willReturn([$game]);
    }
}
