<?php

namespace App\Command\Steam;

use App\Service\Steam\GamesOwnedService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class UpdateRecentlyPlayedGamesCommand
 */
class UpdateRecentlyPlayedGamesCommand extends ContainerAwareCommand
{
    protected function configure(): void
    {
        $this->setName('steam:update:recent');
        $this->setDescription('Synchronizes local game information with recently played steam games');
    }

    /**
     * @var GamesOwnedService
     */
    private $gamesOwnedService;

    /**
     * UpdateAllGamesCommand constructor.
     *
     * @param GamesOwnedService $gamesOwnedService
     */
    public function __construct(GamesOwnedService $gamesOwnedService)
    {
        parent::__construct();
        $this->gamesOwnedService = $gamesOwnedService;
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
        $this->gamesOwnedService->resetRecentGames();
        $mySteamGames = $this->getGamesOwnedService()->getMyRecentlyPlayedGames();

        foreach ($mySteamGames as $mySteamGame) {
            $status = $this->gamesOwnedService->createOrUpdateGame($mySteamGame['appid']);
            $output->write($status);
        }

        $output->writeln(['', '', 'Summary:']);
        $status = $this->gamesOwnedService->getSummary();
        foreach ($status as $key => $value) {
            $output->writeln('- ' . sprintf($key, $value));
        }

        $errors = $this->gamesOwnedService->getErrors();
        if (!empty($errors)) {
            $output->writeln(['', 'Following Steam AppIDs threw errors while receiving information']);
            foreach ($errors as $error) {
                $output->writeln('- ' . $error);
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
