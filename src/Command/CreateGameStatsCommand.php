<?php

namespace App\Command;

use App\Service\Steam\CreateGamesForAllUsersService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CreateNewGamesCommand
 */
class CreateGameStatsCommand extends Command
{

    /**
     * @var CreateGamesForAllUsersService
     */
    private $createGamesForAllUsersService;

    /**
     * CreateNewGamesCommand constructor.
     * @param CreateGamesForAllUsersService $createGamesForAllUsersService
     */
    public function __construct(CreateGamesForAllUsersService $createGamesForAllUsersService)
    {
        parent::__construct();
        $this->createGamesForAllUsersService = $createGamesForAllUsersService;
    }


    protected function configure(): void
    {
        $this->setName('user:create:gamestats');
        $this->setDescription('Creates new games based on steam');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->createGamesForAllUsersService->execute();
    }
}
