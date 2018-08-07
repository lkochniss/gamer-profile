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
     * @var User
     */
    private $user;

    /**
     * @var Game
     */
    private $game;

    /**
     * Playtime constructor.
     * @param User $user
     * @param Game $game
     */
    public function __construct(User $user, Game $game)
    {
        $this->user = $user;
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
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return Game
     */
    public function getGame(): Game
    {
        return $this->game;
    }
}
