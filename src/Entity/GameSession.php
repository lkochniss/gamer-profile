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
     * @var User
     */
    private $user;

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

    public function setSlug(): void
    {
        $this->slug = $this->slugify($this->getCreatedAt()->format('Y-m-d-') . $this->getGame()->getName());
    }
}
