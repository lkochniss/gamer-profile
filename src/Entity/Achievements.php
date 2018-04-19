<?php

namespace App\Entity;

/**
 * Class Achievements
 */
class Achievements extends AbstractEntity
{
    /**
     * @var int
     */
    private $playerAchievements;

    /**
     * @var int
     */
    private $overallAchievements;

    /**
     * Achievements constructor.
     * @param array $playerStats
     */
    public function __construct(array $playerStats)
    {
        $this->playerAchievements = 0;
        $this->overallAchievements = 0;

        foreach ($playerStats['playerstats']['achievements'] as $achievement) {
            if ($achievement['achieved'] === 1) {
                $this->playerAchievements ++;
            }

            $this->overallAchievements ++;
        }
    }

    /**
     * @return int
     */
    public function getPlayerAchievements(): int
    {
        return $this->playerAchievements;
    }

    /**
     * @return int
     */
    public function getOverallAchievements(): int
    {
        return $this->overallAchievements;
    }
}
