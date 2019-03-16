<?php

namespace App\Command;

use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class UpdateOldestGamesCommand
 */
class UpdateOldestGamesCommand extends ContainerAwareCommand
{

    /**
     * @var GameRepository
     */
    private $gameRepository;

    /**
     * UpdateOldestGamesCommand constructor.
     * @param GameRepository $gameRepository
     */
    public function __construct(
        GameRepository $gameRepository
    ) {
        parent::__construct();
        $this->gameRepository = $gameRepository;
    }

    protected function configure(): void
    {
        $this->setName('steam:update:games');
        $this->setDescription('Updates 20 least updated games');
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
    }
}
