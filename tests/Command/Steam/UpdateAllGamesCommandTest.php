<?php

namespace tests\App\Command\Steam;

use App\Command\Steam\UpdateAllGamesCommand;
use App\Service\Steam\GamesOwnedService;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class UpdateAllGamesCommandTest
 */
class UpdateAllGamesCommandTest extends KernelTestCase
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

        $this->command = $this->application->find('gamerprofile:synchronize:steam');
        $commandTester = new CommandTester($this->command);
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this->assertContains('Added 1 new game', $output);
    }

    private function addCommandToKernel(): void
    {
        $this->application->add(new UpdateAllGamesCommand($this->gamesOwnedServiceMock));
    }

    private function setGamesOwnedServiceMock():void
    {
        $this->gamesOwnedServiceMock->expects($this->any())
            ->method('getMyGames')
            ->willReturn(new Response(200,[], json_encode($this->getGameResponseData())));
    }

    /**
     * @return array
     */
    private function getGameResponseData(): array
    {
        return [
            'response' => [
                'games_count' => 2,
                'games' => $this->getGamesArray()
            ]
        ];
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
