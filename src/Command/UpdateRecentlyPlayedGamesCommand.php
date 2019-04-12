<?php

namespace App\Command;

use App\Service\Steam\GamesForAllUsersService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class UpdateRecentlyPlayedGamesCommand
 */
class UpdateRecentlyPlayedGamesCommand extends Command
{

    /**
     * @var GamesForAllUsersService
     */
    private $gamesForAllUsersService;

    /**
     * CreateNewGamesCommand constructor.
     * @param GamesForAllUsersService $gamesForAllUsersService
     */
    public function __construct(GamesForAllUsersService $gamesForAllUsersService)
    {
        parent::__construct();
        $this->gamesForAllUsersService = $gamesForAllUsersService;
    }


    protected function configure(): void
    {
        $this->setName('steam:update:recent');
        $this->setDescription('Updates recently played games for all users');
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
        $this->gamesForAllUsersService->updateRecentlyPlayed();
    }
}
