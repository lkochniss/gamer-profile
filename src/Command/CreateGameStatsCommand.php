<?php

namespace App\Command;

use App\Service\GameStats\CreateGameStatsForAllUsersService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CreateNewGamesCommand
 */
class CreateGameStatsCommand extends Command
{

    /**
     * @var CreateGameStatsForAllUsersService
     */
    private $createGameStatsForAllUsersService;

    /**
     * CreateGameStatsCommand constructor.
     * @param CreateGameStatsForAllUsersService $createGameStatsForAllUsersService
     */
    public function __construct(CreateGameStatsForAllUsersService $createGameStatsForAllUsersService)
    {
        parent::__construct();
        $this->createGameStatsForAllUsersService = $createGameStatsForAllUsersService;
    }


    protected function configure(): void
    {
        $this->setName('steam:create:stats');
        $this->setDescription('Creates game stats for users based on their owned games');
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
        $this->createGameStatsForAllUsersService->execute();
    }
}
