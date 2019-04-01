<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class GameStats
 */
class GameStats
{
    const OPEN = 'open';
    const PAUSED = 'paused';
    const PLAYING = 'playing';
    const FINISHED = 'finished';
    const GIVEN_UP = 'given_up';

    /**
     * @var int
     */
    private $id;

    /**
     * @var ArrayCollection
     */
    private $gameSessions;

    /**
     * @var Achievement
     */
    private $achievement;

    /**
     * @var Playtime
     */
    private $playtime;

    /**
     * @var ArrayCollection
     */
    private $playtimePerMonth;

    /**
     * @var Game
     */
    private $game;

    /**
     * @var int
     */
    private $steamUserId;

    /**
     * @var string
     */
    private $status;
    /**
     * @var int
     */
    private $steamUserId;

    public function setSteamUserID(int $steamUserId): void
    {
        $this->steamUserId = $steamUserId;
    }

    /**
     * GameStats constructor.
     * @param int $steamUserId
     * @param Game $game
     * @param Achievement $achievement
     * @param Playtime $playtime
     */
    public function __construct(int $steamUserId, Game $game, Achievement $achievement, Playtime $playtime)
    {
        $this->achievement = $achievement;
        $this->playtime = $playtime;
        $this->game = $game;
        $this->steamUserId = $steamUserId;
        $this->status = $this::OPEN;

        $this->playtimePerMonth = new ArrayCollection();
        $this->gameSessions = new ArrayCollection();
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Achievement
     */
    public function getAchievement(): Achievement
    {
        return $this->achievement;
    }

    /**
     * @return Playtime
     */
    public function getPlaytime(): Playtime
    {
        return $this->playtime;
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
    public function getSteamUserId(): int
    {
        return $this->steamUserId;
    }

    /**
     * @param GameSession $gameSession
     */
    public function addGameSession(GameSession $gameSession): void
    {
        if (!$this->gameSessions->contains($gameSession)) {
            $this->gameSessions->add($gameSession);
        }
    }

    /**
     * @return GameSession[]
     */
    public function getGameSessions(): array
    {
        return $this->gameSessions->toArray();
    }

    /**
     * @param PlaytimePerMonth $playtimePerMonth
     */
    public function addPlaytimePerMonth(PlaytimePerMonth $playtimePerMonth): void
    {
        if (!$this->playtimePerMonth->contains($playtimePerMonth)) {
            $this->playtimePerMonth->add($playtimePerMonth);
        }
    }

    /**
     * @return Playtime[]
     */
    public function getPlaytimePerMonths(): array
    {
        return $this->playtimePerMonth->toArray();
    }

    public function setStatusOpen()
    {
        $this->status = $this::OPEN;
    }

    /**
     * @return bool
     */
    public function isStatusOpen()
    {
        return $this->status === $this::OPEN || $this->status === null;
    }

    public function setStatusPaused()
    {
        $this->status = $this::PAUSED;
    }

    /**
     * @return bool
     */
    public function isStatusPaused()
    {
        return $this->status === $this::PAUSED;
    }

    public function setStatusPlaying()
    {
        $this->status = $this::PLAYING;
    }

    /**
     * @return bool
     */
    public function isStatusPlaying()
    {
        return $this->status === $this::PLAYING;
    }


    public function setStatusFinished()
    {
        $this->status = $this::FINISHED;
    }

    /**
     * @return bool
     */
    public function isStatusFinished()
    {
        return $this->status === $this::FINISHED;
    }

    public function setStatusGivenUp()
    {
        $this->status = $this::GIVEN_UP;
    }

    /**
     * @return bool
     */
    public function isStatusGivenUp()
    {
        return $this->status === $this::GIVEN_UP;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status ? $this->status : $this::OPEN;
    }
}
