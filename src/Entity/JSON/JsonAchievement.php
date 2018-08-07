<?php

namespace App\Entity\JSON;

/**
 * Class JsonAchievement
 */
class JsonAchievement
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
    public function __construct(array $playerStats = [])
    {
        $this->playerAchievements = 0;
        $this->overallAchievements = 0;

        if (array_key_exists('playerstats', $playerStats) &&
            array_key_exists('achievements', $playerStats['playerstats'])
        ) {
            foreach ($playerStats['playerstats']['achievements'] as $achievement) {
                if ($achievement['achieved'] === 1) {
                    $this->playerAchievements++;
                }

                $this->overallAchievements++;
            }
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
