<?php

namespace App\Command;

use App\Repository\GameRepository;
use App\Service\Entity\GameService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class UpdateOldestGamesCommand
 */
class UpdateOldestGamesCommand extends ContainerAwareCommand
{
    /**
     * @var GameService
     */
    private $gameService;

    /**
     * @var GameRepository
     */
    private $gameRepository;

    /**
     * UpdateOldestGamesCommand constructor.
     * @param GameService $gameService
     * @param GameRepository $gameRepository
     * @SuppressWarnings(PHPMD.LongVariableName)
     */
    public function __construct(
        GameService $gameService,
        GameRepository $gameRepository
    ) {
        parent::__construct();
        $this->gameService = $gameService;
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
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @SuppressWarnings("unused")
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(['', 'Starting:']);
        $localGames = $this->gameRepository->getLeastUpdatedGames(20);

        foreach ($localGames as $game) {
            $status = $this->gameService->update($game->getSteamAppId());
            $output->write($status);
        }
    }
}
