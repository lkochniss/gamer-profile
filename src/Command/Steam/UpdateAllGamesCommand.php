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
        $this->setName('gamerprofile:synchronize:steam');
        $this->setDescription('Synchronizes local game information with steam');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mySteamGames = $this->gamesOwnedService->getMyGames();

        foreach ($mySteamGames as $mySteamGame) {
            $status = $this->gamesOwnedService->createOrUpdateGame($mySteamGame['appid']);
            $output->write($status);
            sleep(1);
        }

        $status = $this->gamesOwnedService->synchronizeMyGames();
        foreach ($status as $key => $value) {
            $output->writeln( sprintf($key, $value));
        }
    }
}
