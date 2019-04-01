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
     * Achievement constructor.
     * @param int $steamUserId
     * @param Game $game
     */
    public function __construct(int $steamUserId, Game $game)
    {
        $this->steamUserId = $steamUserId;
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
