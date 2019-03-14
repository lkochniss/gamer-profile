<?php

namespace App\Command;

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
    private $service;

    /**
     * CreateNewGamesCommand constructor.
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     */
    public function __construct(GameInformationService $gameInformationService) {
        parent::__construct();

        $this->service = $gameInformationService;
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
        $this->service->getGameInformationForSteamAppId(10);
    }
}
