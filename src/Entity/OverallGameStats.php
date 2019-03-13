<?php

namespace App\Entity;

/**
 * Class OverallGameStats
 */
class OverallGameStats extends AbstractEntity
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
    private $recentPlaytime = 0;

    /**
     * @var int
     */
    private $overallPlaytime = 0;

    /**
     * @var int
     */
    private $gameSessions = 0;

    /**
     * @var User
     */
    private $user;

    /**
     * OverallGameStats constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param int $number
     */
    public function addToOverallAchievements(int $number): void
    {
        $this->overallAchievements += $number;
    }

    /**
     * @return int
     */
    public function getOverallAchievements(): int
    {
        return $this->overallAchievements;
    }

    /**
     * @param int $number
     */
    public function addToPlayerAchievements(int $number): void
    {
        $this->playerAchievements += $number;
    }

    /**
     * @return int
     */
    public function getPlayerAchievements(): int
    {
        return $this->playerAchievements;
    }

    /**
     * @param int $number
     */
    public function addToRecentPlaytime(int $number): void
    {
        $this->recentPlaytime += $number;
    }

    /**
     * @return int
     */
    public function getRecentPlaytime(): int
    {
        return $this->recentPlaytime;
    }

    /**
     * @param int $number
     */
    public function addToOverallPlaytime(int $number): void
    {
        $this->overallPlaytime += $number;
    }

    /**
     * @return int
     */
    public function getOverallPlaytime(): int
    {
        return $this->overallPlaytime;
    }

    public function addGameSessions(): void
    {
        $this->gameSessions ++;
    }

    /**
     * @param int $sessions
     */
    public function setGameSessions(int $sessions): void
    {
        $this->gameSessions = $sessions;
    }

    /**
     * @return int
     */
    public function getGameSessions(): int
    {
        return $this->gameSessions;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}
