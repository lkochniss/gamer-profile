<?php

namespace tests\App\Command\Steam;

use App\Command\Steam\UpdateRecentlyPlayedGamesCommand;
use App\Service\ReportService;
use App\Service\Steam\Entity\UpdateGameInformationService;
use App\Service\Steam\Entity\UpdateUserInformationService;
use App\Service\Steam\GamesOwnedService;
use App\Service\Steam\Transformation\GameUserInformationService;
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
    private $updateGameInformationServiceMock;

    /**
     * @var MockObject
     */
    private $updateUserInformationServiceMock;

    /**
     * @var MockObject
     */
    private $gameUserInformationServiceMock;

    public function setUp(): void
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $this->updateGameInformationServiceMock = $this->createMock(UpdateGameInformationService::class);
        $this->updateUserInformationServiceMock = $this->createMock(UpdateUserInformationService::class);
        $this->gameUserInformationServiceMock = $this->createMock(GameUserInformationService::class);
        $this->application = new Application($kernel);
    }

    public function testCommandExecute(): void
    {
        $this->updateGameInformationServiceMock->expects($this->any())
            ->method('updateGameInformationForSteamAppId')
            ->with(1)
            ->willReturn('U');

        $this->updateUserInformationServiceMock->expects($this->any())
            ->method('updateUserInformationForSteamAppId')
            ->with(1)
            ->willReturn('U');

        $this->gameUserInformationServiceMock->expects($this->any())
            ->method('getRecentlyPlayedGames')
            ->willReturn($this->getRecentlyGamesArray());

        $this->application->add(new UpdateRecentlyPlayedGamesCommand(
            $this->updateGameInformationServiceMock,
            $this->updateUserInformationServiceMock,
            $this->gameUserInformationServiceMock
        ));

        $this->command = $this->application->find('steam:update:recent');
        $commandTester = new CommandTester($this->command);
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this->assertContains('U', $output);
    }

    /**
     * @return array
     */
    private function getRecentlyGamesArray(): array
    {
        return [
            [
                'appid' => 1,
                'playtime_forever' => 20,
                'playtime_2weeks' => 10
            ]
        ];
    }
}
