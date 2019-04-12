<?php


namespace App\Tests\Command;

use App\Command\UpdateRecentlyPlayedGamesCommand;
use App\Service\Steam\GamesForAllUsersService;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class UpdateRecentlyPlayedGamesCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $serviceMock = $this->createMock(GamesForAllUsersService::class);
        $serviceMock->expects($this->once())
            ->method('updateRecentlyPlayed');

        $application->add(new UpdateRecentlyPlayedGamesCommand($serviceMock));

        $command = $application->find('steam:update:recent');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command'  => $command->getName(),
        ]);
    }
}
