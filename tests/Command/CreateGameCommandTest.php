<?php


namespace App\Tests\Command;

use App\Command\CreateGameCommand;
use App\Service\Steam\GamesForAllUsersService;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CreateGameCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $mockService = $this->createMock(GamesForAllUsersService::class);
        $mockService->expects($this->once())
            ->method('create');

        $application->add(new CreateGameCommand($mockService));

        $command = $application->find('steam:create:games');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command'  => $command->getName(),
        ]);
    }
}
