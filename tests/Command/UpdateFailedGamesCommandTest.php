<?php


namespace App\Tests\Command;

use App\Command\UpdateFailedGamesCommand;
use App\Service\Steam\GameService;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class UpdateFailedGamesCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $serviceMock = $this->createMock(GameService::class);
        $serviceMock->expects($this->once())
            ->method('updateFailed');

        $application->add(new UpdateFailedGamesCommand($serviceMock));

        $command = $application->find('steam:update:failed');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command'  => $command->getName(),
        ]);
    }
}
