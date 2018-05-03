<?php

namespace App\Entity;

/**
 * Class BasicInformationChangeSet
 */
class BasicInformationChangeSet extends AbstractEntity
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
    private $recentlyPlayed = 0;

    /**
     * @var int
     */
    private $timePlayed = 0;

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
     * @return int
     */
    public function getRecentlyPlayed(): int
    {
        return $this->recentlyPlayed;
    }

    /**
     * @param int $recentlyPlayed
     */
    public function setRecentlyPlayed(int $recentlyPlayed): void
    {
        $this->recentlyPlayed = $recentlyPlayed;
    }

    /**
     * @return int
     */
    public function getTimePlayed(): int
    {
        return $this->timePlayed;
    }

    /**
     * @param int $timePlayed
     */
    public function setTimePlayed(int $timePlayed): void
    {
        $this->timePlayed = $timePlayed;
    }
}
