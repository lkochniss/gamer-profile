<?php

namespace App\Command\Steam;

use App\Repository\GameRepository;
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
     * @var GameRepository
     */
    private $gameRepository;

    /**
     * UpdateRecentlyPlayedGamesCommand constructor.
     * @param UpdateGameInformationService $updateGameInformationService
     * @param UpdateUserInformationService $updateUserInformationService
     * @param GameUserInformationService $gameUserInformationService
     * @param GameRepository $gameRepository
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     */
    public function __construct(
        UpdateGameInformationService $updateGameInformationService,
        UpdateUserInformationService $updateUserInformationService,
        GameUserInformationService $gameUserInformationService,
        GameRepository $gameRepository
    ) {
        parent::__construct();
        $this->updateGameInformationService = $updateGameInformationService;
        $this->updateUserInformationService = $updateUserInformationService;
        $this->gameUserInformationService = $gameUserInformationService;
        $this->gameRepository = $gameRepository;
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
     * @throws \Nette\Utils\JsonException
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     * @SuppressWarnings("unused")
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $oldRecentlyPlayedGames = $this->gameRepository->getRecentlyPlayedGames();

        foreach ($oldRecentlyPlayedGames as $oldRecentlyPlayedGame) {
            $oldRecentlyPlayedGame->setRecentlyPlayed(0);
            $this->gameRepository->save($oldRecentlyPlayedGame);
        }

        $mySteamGames = $this->gameUserInformationService->getRecentlyPlayedGames();
        foreach ($mySteamGames as $mySteamGame) {
            $steamAppId =  $mySteamGame['appid'];
            $status = $this->updateGameInformationService->updateGameInformationForSteamAppId($steamAppId);
            $output->write($status);

            $status = $this->updateUserInformationService->addSessionForSteamAppId($steamAppId);
            $output->write($status);

            $status = $this->updateUserInformationService->updateUserInformationForSteamAppId($steamAppId);
            $output->write($status);

            $status = $this->updateUserInformationService->updateAchievementsForSteamAppId($steamAppId);
            $output->write($status);
        }
    }
}
