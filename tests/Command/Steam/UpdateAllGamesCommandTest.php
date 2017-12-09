<?php

namespace tests\App\Command\Steam;

use App\Command\Steam\UpdateAllGamesCommand;
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

        $application = new Application($kernel);
        $application->add(new UpdateAllGamesCommand());

        $this->command = $application->find('gamerprofile:synchronize:steam');
    }

    public function testCommandExecute(): void
    {
        $commandTester = new CommandTester($this->command);
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this->assertContains('Added 1 new game', $output);
    }
}
