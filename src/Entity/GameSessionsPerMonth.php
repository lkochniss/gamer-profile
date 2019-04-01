<?php

namespace App\Entity;

/**
 * Class GameSessionsPerMonth
 */
class GameSessionsPerMonth extends AbstractEntity
{
    /**
     * @var \DateTime
     */
    private $month;

    /**
     * @var int
     */
    private $duration;

    /**
     * @var Game
     */
    private $game;

    /**
     * @var int
     */
    private $steamUserId;

    /**
     * @var int
     */
    private $steamUserId;

    public function setSteamUserID(int $steamUserId): void
    {
        $this->steamUserId = $steamUserId;
    }

    /**
     * GameSessionsPerMonth constructor.
     * @param \DateTime $month
     * @param Game $game
     * @param int $steamUserId
     */
    public function __construct(\DateTime $month, Game $game, int $steamUserId)
    {
        $this->month = $month;
        $this->game = $game;
        $this->steamUserId = $steamUserId;
        $this->duration = 0;
    }

    /**
     * @return \DateTime
     */
    public function getMonth(): \DateTime
    {
        return $this->month;
    }

    /**
     * @return int
     */
    public function getDuration(): int
    {
        return $this->duration;
    }

    /**
     * @param int $playTime
     */
    public function addToDuration(int $playTime): void
    {
        $this->duration += $playTime;
    }

    /**
     * @return Game
     */
    public function getGame(): Game
    {
        return $this->game;
    }

    /**
     * @return string
     */
    public function getSteamUserId(): int
    {
        return $this->steamUserId;
    }
}
