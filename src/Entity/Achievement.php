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
     * @var int
     */
    private $steamUserId;

    public function setSteamUserID(int $steamUserId): void
    {
        $this->steamUserId = $steamUserId;
    }

    /**
     * Achievement constructor.
     * @param User $user
     * @param Game $game
     */
    public function __construct(User $user, Game $game)
    {
        $this->user = $user;
        $this->game = $game;
        $this->playerAchievements = 0;
        $this->overallAchievements = 0;
    }

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
     * @return Game
     */
    public function getGame(): Game
    {
        return $this->game;
    }
}
