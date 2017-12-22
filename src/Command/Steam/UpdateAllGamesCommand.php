<?php

namespace App\Command\Steam;

use App\Service\Steam\GamesOwnedService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class UpdateAllGamesCommand
 */
class UpdateAllGamesCommand extends ContainerAwareCommand
{
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

    protected function configure(): void
    {
        $this->setName('steam:update:all');
        $this->setDescription('Synchronizes local game information with steam');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mySteamGames = $this->gamesOwnedService->getAllMyGames();

        $sleepCounter = 0;
        foreach ($mySteamGames as $mySteamGame) {
            $sleepCounter++;
            $status = $this->gamesOwnedService->createOrUpdateGame($mySteamGame['appid']);
            $output->write($status);

            if ($sleepCounter % 100 === 0) {
                $output->writeln('S');
                sleep(5);
            }
        }

        $status = $this->gamesOwnedService->getSummary();
        foreach ($status as $key => $value) {
            $output->writeln('');
            $output->writeln(sprintf($key, $value));
        }

        foreach ($this->gamesOwnedService->getErrors() as $error) {
            $output->writeln($error);
        }
    }
}
