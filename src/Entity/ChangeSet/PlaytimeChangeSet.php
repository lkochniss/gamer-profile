<?php

namespace App\Entity\ChangeSet;

use App\Entity\User;

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
     * @var User
     */
    private $user;

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
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }
}
