<?php

namespace tests\App\Command\Steam;

use App\Command\Steam\CreateNewGamesCommand;
use App\Service\Steam\Entity\CreateNewGameService;
use App\Service\Steam\Transformation\GameUserInformationService;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class CreateNewGamesCommandTest
 */
class CreateNewGamesCommandTest extends KernelTestCase
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
    private $gameUserInformationServiceMock;

    /**
     * @var MockObject
     */
    private $createNewGameServiceMock;

    public function setUp(): void
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $this->gameUserInformationServiceMock = $this->createMock(GameUserInformationService::class);
        $this->createNewGameServiceMock = $this->createMock(CreateNewGameService::class);
        $this->application = new Application($kernel);
    }

    public function testCommandExecute(): void
    {
        $this->gameUserInformationServiceMock->expects($this->any())
            ->method('getAllGames')
            ->willReturn($this->getGamesArray());

        $this->createNewGameServiceMock->expects($this->any())
            ->method('createGameIfNotExist')
            ->with(1)
            ->willReturn('N');

        $this->application->add(new CreateNewGamesCommand(
            $this->gameUserInformationServiceMock,
            $this->createNewGameServiceMock
        ));

        $this->command = $this->application->find('steam:create:new');
        $commandTester = new CommandTester($this->command);
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this->assertContains('N', $output);
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
