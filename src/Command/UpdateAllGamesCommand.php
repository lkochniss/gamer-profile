<?php

namespace App\Command;

use App\Service\Steam\AllGamesService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class UpdateAllGamesCommand
 */
class UpdateAllGamesCommand extends Command
{

    /**
     * @var AllGamesService
     */
    private $allGamesService;

    /**
     * UpdateAllGamesCommand constructor.
     * @param AllGamesService $allGamesService
     */
    public function __construct(AllGamesService $allGamesService)
    {
        parent::__construct();
        $this->allGamesService = $allGamesService;
    }


    protected function configure(): void
    {
        $this->setName('steam:update:all');
        $this->setDescription('Updates all games for all users');
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
        $this->allGamesService->update();
    }
}
