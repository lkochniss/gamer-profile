<?php


namespace App\Tests\Command;

use App\Command\UpdatePlaytimeCommand;
use App\Service\GameStats\UpdatePlaytimeForAllUsersService;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class UpdatePlaytimeCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $serviceMock = $this->createMock(UpdatePlaytimeForAllUsersService::class);
        $serviceMock->expects($this->once())
            ->method('execute');

        $application->add(new UpdatePlaytimeCommand($serviceMock));

        $command = $application->find('steam:update:playtime');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command'  => $command->getName(),
        ]);
    }
}
