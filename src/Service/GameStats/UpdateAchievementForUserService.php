<?php

namespace App\Service\GameStats;

use App\Entity\User;
use App\Repository\GameRepository;
use App\Service\Transformation\GameUserInformationService;

class UpdateAchievementForUserService
{
    /**
     * @var GameUserInformationService
     */
    private $gameUserInformationService;

    /**
     * @var AchievementService
     */
    private $achievementService;

    /**
     * @var GameRepository
     */
    private $gameRepository;

    /**
     * UpdateAchievementForUserService constructor.
     * @param GameUserInformationService $gameUserInformationService
     * @param AchievementService $achievementService
     * @param GameRepository $gameRepository
     */
    public function __construct(
        GameUserInformationService $gameUserInformationService,
        AchievementService $achievementService,
        GameRepository $gameRepository
    ) {
        $this->gameUserInformationService = $gameUserInformationService;
        $this->achievementService = $achievementService;
        $this->gameRepository = $gameRepository;
    }

    /**
     * @param User $user
     */
    public function execute(User $user): void
    {
        $recentlyPlayedGames = $this->gameUserInformationService->getRecentlyPlayedGames($user->getSteamId());

        foreach ($recentlyPlayedGames as $gameArray) {
            $game  = $this->gameRepository->findOneBySteamAppId($gameArray['appid']);
            if (!is_null($game)) {
                $this->achievementService->updateGameForUser($game, $user);
            }
        }
    }
}
