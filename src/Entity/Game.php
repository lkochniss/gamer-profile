<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Game
 */
class Game extends AbstractEntity
{
    /**
     * @var int
     */
    private $steamAppId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var int
     */
    private $recentlyPlayed;

    /**
     * @var int
     */
    private $timePlayed;

    /**
     * @var string
     */
    private $headerImagePath;

    /**
     * @var \DateTime
     */
    private $boughtAt;

    /**
     * @var int
     */
    private $price;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var ArrayCollection
     */
    private $blogPosts;

    /**
     * @var ArrayCollection
     */
    private $gameSessions;

    /**
     * @var ArrayCollection
     */
    private $purchases;

    /**
     * Game constructor.
     */
    public function __construct()
    {
        $this->price = 0;
        $this->currency = 'USD';

        $this->blogPosts = new ArrayCollection();
        $this->gameSessions = new ArrayCollection();
        $this->purchases = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getSteamAppId(): int
    {
        return $this->steamAppId;
    }

    /**
     * @param int $steamAppId
     */
    public function setSteamAppId(int $steamAppId): void
    {
        $this->steamAppId = $steamAppId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description ?: '';
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getRecentlyPlayed(): int
    {
        return $this->recentlyPlayed;
    }

    /**
     * @param int $recentlyPlayed
     */
    public function setRecentlyPlayed(int $recentlyPlayed): void
    {
        $this->recentlyPlayed = $recentlyPlayed;
    }

    /**
     * @return int
     */
    public function getTimePlayed(): int
    {
        return $this->timePlayed;
    }

    /**
     * @param int $timePlayed
     */
    public function setTimePlayed(int $timePlayed): void
    {
        $this->timePlayed = $timePlayed;
    }

    /**
     * @return string
     */
    public function getHeaderImagePath(): string
    {
        return $this->headerImagePath;
    }

    /**
     * @param string $headerImagePath
     */
    public function setHeaderImagePath(string $headerImagePath): void
    {
        $this->headerImagePath = $headerImagePath;
    }

    /**
     * @return \DateTime
     */
    public function getBoughtAt(): \DateTime
    {
        return $this->boughtAt ? $this->boughtAt : new \DateTime();
    }

    /**
     * @param \DateTime $boughtAt
     */
    public function setBoughtAt(\DateTime $boughtAt): void
    {
        $this->boughtAt = $boughtAt;
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
     * @param BlogPost $blogPost
     */
    public function addBlogPost(BlogPost $blogPost): void
    {
        if (!$this->blogPosts->contains($blogPost)) {
            $this->blogPosts->add($blogPost);
            $blogPost->setGame($this);
        }
    }

    /**
     * @param BlogPost $blogPost
     */
    public function removeBlogPost(BlogPost $blogPost): void
    {
        $this->blogPosts->remove($blogPost);
    }

    /**
     * @return array
     */
    public function getBlogPosts(): array
    {
        return $this->blogPosts->toArray();
    }

    /**
     * @param GameSession $gameSession
     */
    public function addGameSession(GameSession $gameSession): void
    {
        if (!$this->gameSessions->contains($gameSession)) {
            $this->gameSessions->add($gameSession);
            $gameSession->setGame($this);
        }
    }

    /**
     * @param GameSession $gameSession
     */
    public function removeGameSession(GameSession $gameSession): void
    {
        $this->gameSessions->remove($gameSession);
    }

    /**
     * @return array
     */
    public function getGameSessions(): array
    {
        return $this->gameSessions->toArray();
    }

    /**
     * @return GameSession|null
     */
    public function getLastGameSession(): ?GameSession
    {
        return $this->gameSessions->last() ?: null;
    }

    /**
     * @param Purchase $purchase
     */
    public function addPurchase(Purchase $purchase): void
    {
        if (!$this->purchases->contains($purchase)) {
            $this->purchases->add($purchase);
            $purchase->setGame($this);
        }
    }

    /**
     * @param Purchase $purchase
     */
    public function removePurchase(Purchase $purchase): void
    {
        $this->purchases->remove($purchase);
    }

    /**
     * @return array
     */
    public function getPurchases(): array
    {
        return $this->purchases->toArray();
    }
}
