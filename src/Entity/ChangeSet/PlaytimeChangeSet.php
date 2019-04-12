<?php

namespace App\Entity\ChangeSet;

/**
 * Class PlaytimeChangeSet
 */
class PlaytimeChangeSet
{
    /**
     * @var int
     */
    private $recentPlaytime = 0;

    /**
     * @var int
     */
    private $overallPlaytime = 0;

    /**
     * @var int
     */
    private $steamUserId;

    /**
     * @return int
     */
    public function getRecentPlaytime(): int
    {
        return $this->recentPlaytime;
    }

    /**
     * @param int $recentPlaytime
     */
    public function setRecentPlaytime(int $recentPlaytime): void
    {
        $this->recentPlaytime = $recentPlaytime;
    }

    /**
     * @return int
     */
    public function getOverallPlaytime(): int
    {
        return $this->overallPlaytime;
    }

    /**
     * @param int $overallPlaytime
     */
    public function setOverallPlaytime(int $overallPlaytime): void
    {
        $this->overallPlaytime = $overallPlaytime;
    }

    /**
     * @return int
     */
    public function getSteamUserId(): int
    {
        return $this->steamUserId;
    }

    /**
     * @param int $steamUserId
     */
    public function setSteamUserId(int $steamUserId): void
    {
        $this->steamUserId = $steamUserId;
    }
}
