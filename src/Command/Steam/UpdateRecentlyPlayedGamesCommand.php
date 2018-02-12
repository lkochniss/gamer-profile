<?php

namespace App\Command\Steam;

use App\Service\Steam\Entity\UpdateGameInformationService;
use App\Service\Steam\Entity\UpdateUserInformationService;
use App\Service\Steam\Transformation\GameUserInformationService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class UpdateRecentlyPlayedGamesCommand
 */
class UpdateRecentlyPlayedGamesCommand extends ContainerAwareCommand
{
    /**
     * @var UpdateGameInformationService
     */
    private $updateGameInformationService;

    /**
     * @var UpdateUserInformationService
     */
    private $updateUserInformationService;

    /**
     * @var GameUserInformationService
     */
    private $gameUserInformationService;

    /**
     * UpdateRecentlyPlayedGamesCommand constructor.
     * @param UpdateGameInformationService $updateGameInformationService
     * @param UpdateUserInformationService $updateUserInformationService
     * @param GameUserInformationService $gameUserInformationService
     */
    public function __construct(
        UpdateGameInformationService $updateGameInformationService,
        UpdateUserInformationService $updateUserInformationService,
        GameUserInformationService $gameUserInformationService
    ) {
        parent::__construct();
        $this->updateGameInformationService = $updateGameInformationService;
        $this->updateUserInformationService = $updateUserInformationService;
        $this->gameUserInformationService = $gameUserInformationService;
    }

    protected function configure(): void
    {
        $this->setName('steam:update:recent');
        $this->setDescription('Synchronizes local game information with recently played steam games');
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
        $mySteamGames = $this->gameUserInformationService->getRecentlyPlayedGames();
        foreach ($mySteamGames as $mySteamGame) {
            $status = $this->updateGameInformationService->updateGameInformationForSteamAppId(
                $mySteamGame['appid']
            );
            $output->write($status);

            $status = $this->updateUserInformationService->addSessionForSteamAppId(
                $mySteamGame['appid']
            );
            $output->write($status);

            $status = $this->updateUserInformationService->updateUserInformationForSteamAppId(
                $mySteamGame['appid']
            );
            $output->write($status);
        }
    }
}
