<?php

namespace App\Command;

use App\Service\Steam\GameService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class UpdateFailedGamesCommand
 */
class UpdateFailedGamesCommand extends Command
{

    /**
     * @var GameService
     */
    private $gameService;

    /**
     * UpdateFailedGamesCommand constructor.
     * @param GameService $gameService
     */
    public function __construct(GameService $gameService)
    {
        parent::__construct();
        $this->gameService = $gameService;
    }


    protected function configure(): void
    {
        $this->setName('steam:update:failed');
        $this->setDescription('Update failed games based on steam');
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
        $this->gameService->updateFailed();
    }
}
