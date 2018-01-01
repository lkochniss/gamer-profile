<?php

namespace App\Entity;

/**
 * Class BlogPost
 */
class BlogPost extends AbstractEntity
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var
     */
    private $content;

    /**
     * @var Game
     */
    private $game;

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->stringTransform($this->title);
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->stringTransform($this->content);
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): void
    {
        $this->content = $content;
    }

    /**
     * @return Game|null
     */
    public function getGame():? Game
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
