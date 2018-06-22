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
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return Game
     */
    public function getGame(): Game
    {
        return $this->game;
    }

    /**
     * @param Game $game
     */
    public function setGame(Game $game): void
    {
        $this->game = $game;
    }
}
