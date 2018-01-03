<?php

namespace App\Command\Steam;

use App\Repository\GameRepository;
use App\Service\Steam\GamesOwnedService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class UpdateOldestGamesCommand
 */
class UpdateOldestGamesCommand extends ContainerAwareCommand
{
    /**
     * @var GamesOwnedService
     */
    private $gamesOwnedService;

    /**
     * @var GameRepository
     */
    private $gameRepository;

    /**
     * UpdateOldestGamesCommand constructor.
     * @param GamesOwnedService $gamesOwnedService
     * @param GameRepository $gameRepository
     */
    public function __construct(GamesOwnedService $gamesOwnedService, GameRepository $gameRepository)
    {
        parent::__construct();
        $this->gamesOwnedService = $gamesOwnedService;
        $this->gameRepository = $gameRepository;
    }

    protected function configure(): void
    {
        $this->setName('steam:update:oldest');
        $this->setDescription('Updates 20 least updated games');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(['', 'Starting:']);
        $this->gamesOwnedService->getAllMyGames();
        $mySteamGames = $this->gameRepository->getLeastUpdatedGames(20);

        foreach ($mySteamGames as $mySteamGame) {
            $status = $this->gamesOwnedService->updateExistingGame($mySteamGame);
            $output->write($status);
        }

        $updates = $this->gamesOwnedService->getUpdates();
        if (!empty($updates)) {
            $output->writeln(['', 'Following Steam AppIDs were updated']);
            foreach ($updates as $update) {
                $output->writeln('- '. $update);
            }
        }

        $errors = $this->gamesOwnedService->getErrors();
        if (!empty($errors)) {
            $output->writeln(['', 'Following Steam AppIDs threw errors while receiving information']);
            foreach ($errors as $error) {
                $output->writeln('- '. $error);
            }
            $output->writeln(['', 'Info: Most errors occur due to country restrictions for a game.']);
        }
    }

    /**
     * @return GamesOwnedService
     */
    protected function getGamesOwnedService(): GamesOwnedService
    {
        return $this->gamesOwnedService;
    }
}
