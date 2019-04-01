<?php

namespace App\Entity;

/**
 * Class Playtime
 */
class Playtime extends AbstractEntity
{
    /**
     * @var int
     */
    private $recentPlaytime;

    /**
     * @var int
     */
    private $overallPlaytime;

    /**
     * @var int
     */
    private $steamUserId;

    /**
     * @var Game
     */
    private $game;
    /**
     * @var int
     */
    private $steamUserId;

    public function setSteamUserID(int $steamUserId): void
    {
        $this->steamUserId = $steamUserId;
    }

    /**
     * Playtime constructor.
     * @param int $steamUserId
     * @param Game $game
     */
    public function __construct(int $steamUserId, Game $game)
    {
        $this->steamUserId = $steamUserId;
        $this->game = $game;
        $this->recentPlaytime = 0;
        $this->overallPlaytime = 0;
    }

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
     * @return Game
     */
    public function getGame(): Game
    {
        return $this->game;
    }
}
