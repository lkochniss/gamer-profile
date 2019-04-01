<?php

namespace App\Entity\ChangeSet;

use App\Entity\User;

/**
 * Class AchievementChangeSet
 */
class AchievementChangeSet
{
    /**
     * @var int
     */
    private $overallAchievements = 0;

    /**
     * @var int
     */
    private $playerAchievements = 0;

    /**
     * @var int
     */
    private $steamUserId;

    /**
     * @return int
     */
    public function getOverallAchievements(): int
    {
        return $this->overallAchievements;
    }

    /**
     * @param int $overallAchievements
     */
    public function setOverallAchievements(int $overallAchievements): void
    {
        $this->overallAchievements = $overallAchievements;
    }

    /**
     * @return int
     */
    public function getPlayerAchievements(): int
    {
        return $this->playerAchievements;
    }

    /**
     * @param int $playerAchievements
     */
    public function setPlayerAchievements(int $playerAchievements): void
    {
        $this->playerAchievements = $playerAchievements;
    }

    /**
     * @return User
     */
    public function getSteamUserId(): int
    {
        return $this->steamUserId;
    }

    /**
     * @param int $steamUserId
     */
    public function setUser(int $steamUserId): void
    {
        $this->steamUserId = $steamUserId;
    }
}
