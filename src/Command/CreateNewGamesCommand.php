<?php

namespace App\Command;

use App\Service\Steam\CreateGamesForAllUsersService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CreateNewGamesCommand
 */
class CreateNewGamesCommand extends Command
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
        $this->setName('steam:create:games');
        $this->setDescription('Creates new games based on steam');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Nette\Utils\JsonException
     *
     * @SuppressWarnings("unused")
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->createGamesForAllUsersService->execute();
    }
}
