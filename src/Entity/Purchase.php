<?php

namespace App\Entity;

/**
 * Class Purchase
 */
class Purchase extends AbstractEntity
{
    const GAME_PURCHASE = 'game-purchase';
    const DLC_PURCHASE = 'dlc-purchase';
    const INGAME_PURCHASE = 'ingame-purchase';
    const OTHER_PURCHASE = 'other-purchase';

    /**
     * @var string
     */
    private $type;

    /**
     * @var int
     */
    private $price;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var string
     */
    private $notice;

    /**
     * @var \DateTime
     */
    private $boughtAt;

    /**
     * @var Game
     */
    private $game;

    /**
     * @var User
     */
    private $user;

    /**
     * @var GameStats
     */
    private $gameStats;

    /**
     * Purchase constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->type = $this::GAME_PURCHASE;
        $this->currency = 'USD';
        $this->price = 0;
        $this->notice = '';
        $this->user = $user;
        $this->boughtAt = new \DateTime();
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price / 100;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price): void
    {
        $this->price = intval($price * 100);
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getNotice(): string
    {
        return $this->stringTransform($this->notice);
    }

    /**
     * @param null|string $notice
     */
    public function setNotice(?string $notice): void
    {
        $this->notice = $notice;
    }

    /**
     * @return \DateTime
     */
    public function getBoughtAt(): \DateTime
    {
        return $this->boughtAt;
    }

    /**
     * @param \DateTime $boughtAt
     */
    public function setBoughtAt(\DateTime $boughtAt): void
    {
        $this->boughtAt = $boughtAt;
    }

    /**
     * @return Game|null
     */
    public function getGame(): ? Game
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
}
