<?php

namespace tests\App\Command\Steam;

use App\Command\Steam\UpdateAllGamesCommand;
use App\Service\Steam\GamesOwnedService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class UpdateAllGamesCommandTest
 */
class UpdateAllGamesCommandTest extends KernelTestCase
{

    private $command;

    public function setUp(): void
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $gamesOwnedServiceMock = $this->createMock(GamesOwnedService::class);

        $application = new Application($kernel);


        $this->command = $application->find('gamerprofile:synchronize:steam');
    }

    public function testCommandExecute(): void
    {
        $commandTester = new CommandTester($this->command);
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this->assertContains('Added 1 new game', $output);
    }

    private function addCommandToKernel()
    {
        $application->add(new UpdateAllGamesCommand());
    }

    private function setUpGamesOwnedService()
    {

        $gamesOwnedServiceMock->expects($this->any())
            ->method('get')
            ->with('/api/appdetails?appids=1')
            ->willReturn(new JsonResponse($this->getGameResponseData()));

        return $gamesOwnedServiceMock;
    }
}
