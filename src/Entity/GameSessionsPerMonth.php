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
     * GameSessionsPerMonth constructor.
     * @param \DateTime $month
     * @param Game $game
     */
    public function __construct(\DateTime $month, Game $game)
    {
        $this->month = $month;
        $this->game = $game;
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
}
