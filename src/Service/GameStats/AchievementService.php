<?php

namespace App\Service\GameStats;

use App\Entity\Achievement;
use App\Entity\Game;
use App\Entity\User;
use App\Repository\AchievementRepository;
use App\Service\Transformation\GameUserInformationService;

class AchievementService
{
    /**
     * @var GameUserInformationService
     */
    private $gameUserInformationService;

    /**
     * @var AchievementRepository
     */
    private $achievementRepository;

    /**
     * CreateAchievementService constructor.
     * @param GameUserInformationService $gameUserInformationService
     * @param AchievementRepository $achievementRepository
     */
    public function __construct(
        GameUserInformationService $gameUserInformationService,
        AchievementRepository $achievementRepository
    ) {
        $this->gameUserInformationService = $gameUserInformationService;
        $this->achievementRepository = $achievementRepository;
    }

    /**
     * @param User $user
     * @param Game $game
     * @return Achievement
     */
    public function create(User $user, Game $game): Achievement
    {
        $achievement = $this->achievementRepository->findOneBy(['game' => $game, 'user' => $user]);

        if (!is_null($achievement)) {
            return $achievement;
        }

        $gameAchievements = $this->gameUserInformationService->getAchievementsForGame(
            $game->getSteamAppId(),
            $user->getSteamId()
        );

        $achievement = new Achievement($user, $game);
        $achievement->setOverallAchievements($gameAchievements->getOverallAchievements());
        $achievement->setPlayerAchievements($gameAchievements->getPlayerAchievements());

        try {
            $this->achievementRepository->save($achievement);
        } catch (\Doctrine\ORM\OptimisticLockException $optimisticLockException) {
        } catch (\Doctrine\ORM\ORMException $ORMException) {
        }

        return $achievement;
    }

    /**
     * @param Achievement $achievement
     * @return Achievement
     */
    public function update(Achievement $achievement): Achievement
    {
        $gameAchievements = $this->gameUserInformationService->getAchievementsForGame(
            $achievement->getGame()->getSteamAppId(),
            $achievement->getUser()->getSteamId()
        );

        $achievement->setOverallAchievements($gameAchievements->getOverallAchievements());
        $achievement->setPlayerAchievements($gameAchievements->getPlayerAchievements());

        try {
            $this->achievementRepository->save($achievement);
        } catch (\Doctrine\ORM\OptimisticLockException $optimisticLockException) {
        } catch (\Doctrine\ORM\ORMException $ORMException) {
        }

        return $achievement;
    }
}
