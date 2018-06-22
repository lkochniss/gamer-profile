<?php

namespace App\Entity;

/**
 * Class Achievement
 */
class Achievement extends AbstractEntity
{
    /**
     * @var int
     */
    private $playerAchievements;

    /**
     * @var int
     */
    private $overallAchievements;

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
    public function getPlayerAchievements(): int
    {
        return $this->playerAchievements;
    }

    /**
     * @param int $playerAchievements
     */
    public function setPlayerAchievements(int $playerAchievements): void
    {
        $this->playerAchievements = $playerAchievements;
    }

    /**
     * @return int
     */
    public function getOverallAchievements(): int
    {
        return $this->overallAchievements;
    }

    /**
     * @param int $overallAchievements
     */
    public function setOverallAchievements(int $overallAchievements): void
    {
        $this->overallAchievements = $overallAchievements;
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
