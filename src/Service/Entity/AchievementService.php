<?php

namespace App\Service\Entity;

use App\Entity\Achievement;
use App\Entity\Game;
use App\Entity\User;
use App\Repository\AchievementRepository;
use App\Service\Transformation\GameUserInformationService;

/**
 * Class AchievementService
 */
class AchievementService
{
    /**
     * @var GameUserInformationService
     */
    private $userInformationService;

    /**
     * @var AchievementRepository
     */
    private $achievementRepository;

    /**
     * UpdatePlaytimeService constructor.
     * @param GameUserInformationService $userInformationService
     * @param AchievementRepository $achievementRepository
     */
    public function __construct(
        GameUserInformationService $userInformationService,
        AchievementRepository $achievementRepository
    ) {
        $this->userInformationService = $userInformationService;
        $this->achievementRepository = $achievementRepository;
    }

    /**
     * @param Game $game
     * @param User $user
     * @return Achievement
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createIfNotExists(Game $game, User $user): Achievement
    {
        $achievement = $this->achievementRepository->findOneBy(['game' => $game, 'user' => $user]);

        if (!is_null($achievement)) {
            return $achievement;
        }

        $achievement = new Achievement($user, $game);

        $updatedInformation = $this->userInformationService->getAchievementsForGame(
            $game->getSteamAppId(),
            $user->getSteamId()
        );

        $achievement->setPlayerAchievements($updatedInformation->getPlayerAchievements());
        $achievement->setOverallAchievements($updatedInformation->getOverallAchievements());

        $this->achievementRepository->save($achievement);

        return $achievement;
    }

    /**
     * @param Achievement $achievement
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(Achievement $achievement): string
    {
        $updatedInformation = $this->userInformationService->getAchievementsForGame(
            $achievement->getGame()->getSteamAppId(),
            $achievement->getUser()->getSteamId()
        );

        $achievement->setPlayerAchievements($updatedInformation->getPlayerAchievements());
        $achievement->setOverallAchievements($updatedInformation->getOverallAchievements());

        $this->achievementRepository->save($achievement);

        return 'U';
    }
}
