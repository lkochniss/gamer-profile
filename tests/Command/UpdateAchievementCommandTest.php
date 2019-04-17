<?php


namespace App\Tests\Command;

use App\Command\UpdateAchievementCommand;
use App\Service\GameStats\UpdateAchievementForAllUsersService;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class UpdateAchievementCommandTest extends KernelTestCase
{
    public function testExecuteShouldCallRecently()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $serviceMock = $this->createMock(UpdateAchievementForAllUsersService::class);
        $serviceMock->expects($this->once())
            ->method('recently');

        $application->add(new UpdateAchievementCommand($serviceMock));

        $command = $application->find('steam:update:achievement');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command'  => $command->getName(),
        ]);
    }

    public function testExecuteShouldCallNoneExisting()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $serviceMock = $this->createMock(UpdateAchievementForAllUsersService::class);
        $serviceMock->expects($this->once())
            ->method('noneExisting');

        $application->add(new UpdateAchievementCommand($serviceMock));

        $command = $application->find('steam:update:achievement');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command'  => $command->getName(),
        ]);
    }
}
