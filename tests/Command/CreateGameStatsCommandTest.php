<?php


namespace App\Tests\Command;

use App\Command\CreateGameStatsCommand;
use App\Service\GameStats\CreateGameStatsForAllUsersService;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CreateGameStatsCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $serviceMock = $this->createMock(CreateGameStatsForAllUsersService::class);
        $serviceMock->expects($this->once())
            ->method('execute');

        $application->add(new CreateGameStatsCommand($serviceMock));

        $command = $application->find('steam:create:stats');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command'  => $command->getName(),
        ]);
    }
}
