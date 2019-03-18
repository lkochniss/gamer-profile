<?php

namespace App\Command;

use App\Service\GameStats\UpdatePlaytimeForAllUsersService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class UpdatePlaytimeCommand
 */
class UpdatePlaytimeCommand extends Command
{
    /**
     * @var UpdatePlaytimeForAllUsersService
     */
    private $updatePlaytimeForAllUsersService;

    /**
     * UpdatePlaytimeCommand constructor.
     * @param UpdatePlaytimeForAllUsersService $updatePlaytimeForAllUsersService
     */
    public function __construct(UpdatePlaytimeForAllUsersService $updatePlaytimeForAllUsersService)
    {
        parent::__construct();
        $this->updatePlaytimeForAllUsersService = $updatePlaytimeForAllUsersService;
    }


    protected function configure(): void
    {
        $this->setName('steam:update:playtime');
        $this->setDescription('Updates playtime for recently played games');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     *
     * @SuppressWarnings("unused")
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->updatePlaytimeForAllUsersService->execute();
    }
}
