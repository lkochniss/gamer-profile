<?php

namespace tests\App\Command\Steam;

use App\Command\Steam\UpdateOldestGamesCommand;
use App\Entity\Game;
use App\Repository\GameRepository;
use App\Service\ReportService;
use App\Service\Steam\GamesOwnedService;
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
    private $gamesOwnedServiceMock;

    /**
     * @var MockObject
     */
    private $gameRepositoryMock;

    public function setUp(): void
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $this->gamesOwnedServiceMock = $this->createMock(GamesOwnedService::class);
        $this->gameRepositoryMock = $this->createMock(GameRepository::class);
        $this->application = new Application($kernel);
    }

    public function testCommandExecute(): void
    {
        $this->setGamesOwnedServiceMock();
        $this->setGameRepositoryMock();
        $this->addCommandToKernel();

        $this->command = $this->application->find('steam:update:oldest');
        $commandTester = new CommandTester($this->command);
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this->assertContains('Following Steam Games were updated', $output);
        $this->assertContains('Demo Game', $output);
    }

    private function addCommandToKernel(): void
    {
        $this->application->add(new UpdateOldestGamesCommand($this->gamesOwnedServiceMock, $this->gameRepositoryMock));
    }

    private function setGamesOwnedServiceMock():void
    {
        $this->gamesOwnedServiceMock->expects($this->any())
            ->method('getAllMyGames')
            ->willReturn($this->getGamesArray());

        $this->gamesOwnedServiceMock->expects($this->any())
            ->method('updateExistingGame')
            ->willReturn('U');

        $this->gamesOwnedServiceMock->expects($this->any())
            ->method('getUpdates')
            ->willReturn([ReportService::UPDATED_GAME => 'Demo Game']);
    }

    private function setGameRepositoryMock():void
    {
        $this->gameRepositoryMock->expects($this->any())
            ->method('getLeastUpdatedGames')
            ->with(20)
            ->willReturn([new Game()]);
    }

    /**
     * @return array
     */
    private function getGamesArray(): array
    {
        return [
            [
                'appid' => 1,
                'playtime_forever' => 0
            ]
        ];
    }
}
