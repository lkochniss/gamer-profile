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
     * @var int
     */
    private $statusOpen = 0;

    /**
     * @var int
     */
    private $statusPaused = 0;

    /**
     * @var int
     */
    private $statusPlaying = 0;

    /**
     * @var int
     */
    private $statusFinished = 0;

    /**
     * @var int
     */
    private $statusGivenUp = 0;

    /**
     * @var int
     */
    private $numberOfGames = 0;

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

    /**
     * @return int
     */
    public function getStatusOpen(): int
    {
        return $this->statusOpen;
    }

    /**
     * @param int $statusOpen
     */
    public function setStatusOpen(int $statusOpen): void
    {
        $this->statusOpen = $statusOpen;
    }

    /**
     * @return int
     */
    public function getStatusPaused(): int
    {
        return $this->statusPaused;
    }

    /**
     * @param int $statusPaused
     */
    public function setStatusPaused(int $statusPaused): void
    {
        $this->statusPaused = $statusPaused;
    }

    /**
     * @return int
     */
    public function getStatusPlaying(): int
    {
        return $this->statusPlaying;
    }

    /**
     * @param int $statusPlaying
     */
    public function setStatusPlaying(int $statusPlaying): void
    {
        $this->statusPlaying = $statusPlaying;
    }

    /**
     * @return int
     */
    public function getStatusFinished(): int
    {
        return $this->statusFinished;
    }

    /**
     * @param int $statusFinished
     */
    public function setStatusFinished(int $statusFinished): void
    {
        $this->statusFinished = $statusFinished;
    }

    /**
     * @return int
     */
    public function getStatusGivenUp(): int
    {
        return $this->statusGivenUp;
    }

    /**
     * @param int $statusGivenUp
     */
    public function setStatusGivenUp(int $statusGivenUp): void
    {
        $this->statusGivenUp = $statusGivenUp;
    }

    /**
     * @return int
     */
    public function getNumberOfGames(): int
    {
        return $this->numberOfGames;
    }

    /**
     * @param int $numberOfGames
     */
    public function setNumberOfGames(int $numberOfGames): void
    {
        $this->numberOfGames = $numberOfGames;
    }
}
