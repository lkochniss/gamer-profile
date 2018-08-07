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
     * @var float
     */
    private $investedMoney = 0.0;

    /**
     * @var float
     */
    private $wastedMoney = 0.0;

    /**
     * @var string
     */
    private $currency;

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
        $this->currency = getenv('DEFAULT_CURRENCY');
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
     * @param float $number
     */
    public function addToInvestedMoney(float $number): void
    {
        $this->investedMoney += $number;
    }

    public function resetInvestedMoney(): void
    {
        $this->investedMoney = 0;
    }

    /**
     * @return float
     */
    public function getInvestedMoney(): float
    {
        return $this->investedMoney;
    }

    /**
     * @param float $number
     */
    public function addToWastedMoney(float $number): void
    {
        $this->wastedMoney += $number;
    }

    public function resetWastedMoney(): void
    {
        $this->wastedMoney = 0;
    }

    /**
     * @return float
     */
    public function getWastedMoney(): float
    {
        return $this->wastedMoney;
    }


    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}
