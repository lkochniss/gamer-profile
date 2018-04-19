<?php

namespace App\Command\Steam;

use App\Repository\GameRepository;
use App\Service\Steam\Entity\UpdateUserInformationService;
use App\Service\Steam\Transformation\GameUserInformationService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * Class AddAchievementsToAllGamesCommand
 */
class AddAchievementsToAllGamesCommand extends ContainerAwareCommand
{
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
     * @param UpdateUserInformationService $updateUserInformationService
     * @param GameUserInformationService $gameUserInformationService
     * @param GameRepository $gameRepository
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     */
    public function __construct(
        UpdateUserInformationService $updateUserInformationService,
        GameUserInformationService $gameUserInformationService,
        GameRepository $gameRepository
    )
    {
        parent::__construct();
        $this->updateUserInformationService = $updateUserInformationService;
        $this->gameUserInformationService = $gameUserInformationService;
    }

    protected function configure(): void
    {
        $this->setName('steam:update:achievements');
        $this->setDescription('updates achievements for all games');
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
        $mySteamGames = $this->gameUserInformationService->getAllGames();
        foreach ($mySteamGames as $mySteamGame) {
            $status = $this->updateUserInformationService->updateAchievementsForSteamAppId($mySteamGame['appid']);
            $output->write($status);
        }
    }
}
