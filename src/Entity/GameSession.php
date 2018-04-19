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

    public function setSlug(): void
    {
        $this->slug = $this->slugify($this->getCreatedAt()->format('d-m-y-') . $this->getGame()->getName());
    }
}
