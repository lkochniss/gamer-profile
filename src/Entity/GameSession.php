<?php

namespace App\Entity;

/**
 * Class GameSession
 */
class GameSession extends AbstractEntity
{
    /**
     * @var Game
     */
    private $game;

    /**
     * @var int
     */
    private $duration;

    /**
     * @var int
     */
    private $steamUserId;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var int
     */
    private $steamUserId;

    public function setSteamUserID(int $steamUserId): void
    {
        $this->steamUserId = $steamUserId;
    }

    /**
     * GameSession constructor.
     * @param Game $game
     * @param int $steamUserId
     */
    public function __construct(Game $game, int $steamUserId)
    {
        $this->game = $game;
        $this->steamUserId = $steamUserId;
        $this->duration = 0;
        $this->date = new \DateTime('today 00:00:00');
    }

    /**
     * @return Game
     */
    public function getGame(): Game
    {
        return $this->game;
    }

    /**
     * @return int
     */
    public function getDuration(): int
    {
        return $this->duration;
    }

    /**
     * @param int $duration
     */
    public function setDuration(int $duration): void
    {
        $this->duration = $duration;
    }

    /**
     * @param int $duration
     */
    public function addDuration(int $duration): void
    {
        $this->duration += $duration;
    }

    /**
     * @return int
     */
    public function getSteamUserId(): int
    {
        return $this->steamUserId;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }
}
