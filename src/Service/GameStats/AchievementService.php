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
     * @param string $steamUserId
     * @param Game $game
     * @return Achievement
     */
    public function create(string $steamUserId, Game $game): Achievement
    {
        $achievement = $this->achievementRepository->findOneBy(['game' => $game, 'steamUserId' => $steamUserId]);

        if (!is_null($achievement)) {
            return $achievement;
        }

        $gameAchievements = $this->gameUserInformationService->getAchievementsForGame(
            $game->getSteamAppId(),
            $steamUserId
        );

        $achievement = new Achievement($steamUserId, $game);
        $achievement->setOverallAchievements($gameAchievements->getOverallAchievements());
        $achievement->setPlayerAchievements($gameAchievements->getPlayerAchievements());

        try {
            $this->achievementRepository->save($achievement);
        } catch (\Doctrine\ORM\OptimisticLockException $optimisticLockException) {
        } catch (\Doctrine\ORM\ORMException $exception) {
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
            $achievement->getSteamUserId()
        );

        $achievement->setOverallAchievements($gameAchievements->getOverallAchievements());
        $achievement->setPlayerAchievements($gameAchievements->getPlayerAchievements());

        try {
            $this->achievementRepository->save($achievement);
        } catch (\Doctrine\ORM\OptimisticLockException $optimisticLockException) {
        } catch (\Doctrine\ORM\ORMException $exception) {
        }

        return $achievement;
    }

    /**
     * @param Game $game
     * @param string $steamUserId
     */
    public function updateGameForUser(Game $game, string $steamUserId): void
    {
        $achievement = $this->achievementRepository->findOneBy(['game' => $game, 'steamUserId' => $steamUserId]);

        if (!is_null($achievement)) {
            $this->update($achievement);
        }
    }

    /**
     * @param Game $game
     * @param string $steamUserId
     */
    public function updateGameForUserIfNoneExisting(Game $game, string $steamUserId): void
    {
        $achievement = $this->achievementRepository->findOneBy(['game' => $game, 'steamUserId' => $steamUserId]);

        if (!is_null($achievement) && $achievement->getOverallAchievements() === 0) {
            $this->update($achievement);
        }
    }
}
