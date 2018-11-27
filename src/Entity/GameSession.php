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
     * @var \DateTime
     */
    private $day;

    /**
     * GameSession constructor.
     * @param Game $game
     */
    public function __construct(Game $game)
    {
        $this->game = $game;
        $this->day = new \DateTime('today 00:00:00');
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
     * @return \DateTime
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @param \DateTime $day
     * @deprecated only for migration usage
     */
    public function setDay(\DateTime $day): void
    {
        $this->day = $day;
    }

    public function setSlug(): void
    {
        $this->slug = $this->slugify($this->getCreatedAt()->format('Y-m-d-') . $this->getGame()->getName());
    }
}
