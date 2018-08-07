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
     * @var User
     */
    private $user;

    /**
     * @var GameStats
     */
    private $gameStats;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * GameSession constructor.
     * @param User $user
     * @param Game $game
     * @param \DateTime $date
     */
    public function __construct(Game $game, User $user, \DateTime $date)
    {
        $this->date = $date;
        $this->game = $game;
        $this->user = $user;
        $this->duration = 0;
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
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return GameStats
     */
    public function getGameStats(): GameStats
    {
        return $this->gameStats;
    }

    /**
     * @param GameStats $gameStats
     */
    public function setGameStats(GameStats $gameStats): void
    {
        $this->gameStats = $gameStats;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date): void
    {
        $this->date = $date;
    }
}
