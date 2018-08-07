<?php

namespace App\Command;

use App\Repository\AchievementRepository;
use App\Repository\UserRepository;
use App\Service\Entity\AchievementService;
use App\Service\Transformation\GameUserInformationService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class UpdateAchievementsCommand
 */
class UpdateAchievementsCommand extends ContainerAwareCommand
{
    /**
     * @var AchievementService
     */
    private $updateAchievementService;

    /**
     * @var GameUserInformationService
     */
    private $gameUserInformationService;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var AchievementRepository
     */
    private $achievementRepository;

    /**
     * UpdateAchievementCommand constructor.
     * @param AchievementService $createAchievementService
     * @param GameUserInformationService $gameUserInformationService
     * @param UserRepository $userRepository
     * @param AchievementRepository $achievementRepository
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     */
    public function __construct(
        AchievementService $createAchievementService,
        GameUserInformationService $gameUserInformationService,
        UserRepository $userRepository,
        AchievementRepository $achievementRepository
    ) {
        parent::__construct();
        $this->updateAchievementService = $createAchievementService;
        $this->gameUserInformationService = $gameUserInformationService;
        $this->userRepository = $userRepository;
        $this->achievementRepository = $achievementRepository;
    }


    protected function configure(): void
    {
        $this->setName('steam:update:achievements');
        $this->setDescription('Updates achievements for recently played games');
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
        $users = $this->userRepository->findAll();

        foreach ($users as $user) {
            $output->writeln(['', 'Starting user: ' . $user->getSteamId()]);
            $games = $this->gameUserInformationService->getRecentlyPlayedGames($user->getSteamid());

            foreach ($games as $game) {
                $achievement = $this->achievementRepository->findOneBy(['user' => $user, 'game' => $game]);

                $status = 'F';
                if (!is_null($achievement)) {
                    $status = $this->updateAchievementService->update($achievement);
                }
                $output->write($status);
            }
        }
    }
}
