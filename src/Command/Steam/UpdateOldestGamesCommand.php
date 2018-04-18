<?php

namespace App\Command\Steam;

use App\Repository\GameRepository;
use App\Service\Steam\Entity\UpdateGameInformationService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class UpdateOldestGamesCommand
 */
class UpdateOldestGamesCommand extends ContainerAwareCommand
{
    /**
     * @var UpdateGameInformationService
     */
    private $updateGameInformationService;

    /**
     * @var GameRepository
     */
    private $gameRepository;

    /**
     * UpdateOldestGamesCommand constructor.
     * @param UpdateGameInformationService $updateGameInformationService
     * @param GameRepository $gameRepository
     * @SuppressWarnings(PHPMD.LongVariableName)
     */
    public function __construct(
        UpdateGameInformationService $updateGameInformationService,
        GameRepository $gameRepository
    ) {
        parent::__construct();
        $this->updateGameInformationService = $updateGameInformationService;
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
     *
     * @SuppressWarnings("unused")
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(['', 'Starting:']);
        $mySteamGames = $this->gameRepository->getLeastUpdatedGames(20);

        foreach ($mySteamGames as $mySteamGame) {
            $status = $this->updateGameInformationService->updateGameInformationForSteamAppId(
                $mySteamGame->getSteamAppId()
            );
            $output->write($status);
        }
    }
}
