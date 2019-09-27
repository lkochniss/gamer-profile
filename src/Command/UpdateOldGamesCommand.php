<?php

namespace App\Command;

use App\Service\Steam\GameService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class UpdateAllGamesCommand
 */
class UpdateOldGamesCommand extends Command
{

    /**
     * @var GameService
     */
    private $gameService;

    /**
     * UpdateAllGamesCommand constructor.
     * @param GameService $gameService
     */
    public function __construct(GameService $gameService)
    {
        parent::__construct();
        $this->gameService = $gameService;
    }


    protected function configure(): void
    {
        $this->setName('steam:update:old');
        $this->setDescription('Updates oldest games based on updatedAt date');
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
        $this->gameService->updateOldest();
    }
}
