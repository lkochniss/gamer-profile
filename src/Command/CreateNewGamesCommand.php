<?php

namespace App\Command;

use App\Service\SteamGameService;
use App\Service\Transformation\GameInformationService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CreateNewGamesCommand
 */
class CreateNewGamesCommand extends Command
{
    /**
     * @var GameInformationService
     */
    private $steamGameService;

    /**
     * CreateNewGamesCommand constructor.
     * @param SteamGameService $steamGameService
     */
    public function __construct(SteamGameService $steamGameService) {
        parent::__construct();

        $this->steamGameService = $steamGameService;
    }

    protected function configure(): void
    {
        $this->setName('steam:create:games');
        $this->setDescription('Creates new games based on steam');
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
        $this->steamGameService->fetchNewGame();
    }
}
