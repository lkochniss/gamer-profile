<?php

namespace tests\App\Command\Steam;

use App\Command\Steam\UpdateRecentlyPlayedGamesCommand;
use App\Service\ReportService;
use App\Service\Steam\GamesOwnedService;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class UpdateRecentlyPlayedGamesCommandTest
 */
class UpdateRecentlyPlayedGamesCommandTest extends KernelTestCase
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

    public function setUp(): void
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $this->gamesOwnedServiceMock = $this->createMock(GamesOwnedService::class);
        $this->application = new Application($kernel);
    }

    public function testCommandExecute(): void
    {
        $this->setGamesOwnedServiceMock();
        $this->addCommandToKernel();

        $this->command = $this->application->find('steam:update:recent');
        $commandTester = new CommandTester($this->command);
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this->assertContains('Added 1 new games', $output);
    }

    private function addCommandToKernel(): void
    {
        $this->application->add(new UpdateRecentlyPlayedGamesCommand($this->gamesOwnedServiceMock));
    }

    private function setGamesOwnedServiceMock():void
    {
        $this->gamesOwnedServiceMock->expects($this->any())
            ->method('getMyRecentlyPlayedGames')
            ->willReturn($this->getGamesArray());

        $this->gamesOwnedServiceMock->expects($this->any())
            ->method('getSummary')
            ->willReturn([ReportService::NEW_GAME => 1]);
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
