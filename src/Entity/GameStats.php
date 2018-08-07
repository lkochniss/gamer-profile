<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class GameStats
 */
class GameStats
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var ArrayCollection
     */
    private $gameSessions;

    /**
     * @var ArrayCollection
     */
    private $purchases;

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
     * @var User
     */
    private $user;

    /**
     * GameStats constructor.
     * @param User $user
     * @param Game $game
     * @param Achievement $achievement
     * @param Playtime $playtime
     */
    public function __construct(User $user, Game $game, Achievement $achievement, Playtime $playtime)
    {
        $this->achievement = $achievement;
        $this->playtime = $playtime;
        $this->game = $game;
        $this->user = $user;

        $this->purchases = new ArrayCollection();
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
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param GameSession $gameSession
     */
    public function addGameSession(GameSession $gameSession): void
    {
        if (!$this->gameSessions->contains($gameSession)) {
            $this->gameSessions->add($gameSession);
            $gameSession->setGameStats($this);
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
     * @param Purchase $purchase
     */
    public function addPurchase(Purchase $purchase): void
    {
        if (!$this->purchases->contains($purchase)) {
            $this->purchases->add($purchase);
            $purchase->setGameStats($this);
        }
    }

    /**
     * @return Purchase[]
     */
    public function getPurchases(): array
    {
        return $this->purchases->toArray();
    }

    /**
     * -     * @return Purchase|null
     * -     */
    public function getGamePurchase(): ?Purchase
    {
        $purchases = $this->getPurchases();

        foreach ($purchases as $purchase) {
            if ($purchase->getType() == Purchase::GAME_PURCHASE) {
                return $purchase;
            }
        }

        return null;
    }

    /**
     * @param PlaytimePerMonth $playtimePerMonth
     */
    public function addPlaytimePerMonth(PlaytimePerMonth $playtimePerMonth): void
    {
        if (!$this->playtimePerMonth->contains($playtimePerMonth)) {
            $this->playtimePerMonth->add($playtimePerMonth);
            $playtimePerMonth->setGameStats($this);
        }
    }

    /**
     * @return Purchase[]
     */
    public function getPlaytimePerMonths(): array
    {
        return $this->playtimePerMonth->toArray();
    }
}