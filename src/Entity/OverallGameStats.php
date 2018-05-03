<?php

namespace App\Entity;

/**
 * Class OverallGameStats
 */
class OverallGameStats extends AbstractEntity
{
    /**
     * @var string
     */
    private $identifier;

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
     * OverallGameStats constructor.
     */
    public function __construct()
    {
        $this->identifier = getenv('STEAM_USER_ID');
        $this->currency = getenv('DEFAULT_CURRENCY');
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
    public function addToRecentlyPlayed(int $number): void
    {
        $this->recentlyPlayed += $number;
    }

    /**
     * @return int
     */
    public function getRecentlyPlayed(): int
    {
        return $this->recentlyPlayed;
    }

    /**
     * @param int $number
     */
    public function addToTimePlayed(int $number): void
    {
        $this->timePlayed += $number;
    }

    /**
     * @return int
     */
    public function getTimePlayed(): int
    {
        return $this->timePlayed;
    }

    public function addGameSessions(): void
    {
        $this->gameSessions ++;
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
}
