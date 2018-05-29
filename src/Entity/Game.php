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
     * @var int
     */
    private $playerAchievements;

    /**
     * @var int
     */
    private $overallAchievements;

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

        $this->playerAchievements = 0;
        $this->overallAchievements = 0;

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
     * @SuppressWarnings(PHPMD.ShortVariableName)
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

    public function hasBoughtAt(): bool
    {
        return $this->boughtAt? true: false;
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
        return $this->currency ?: getenv('DEFAULT_CURRENCY');
    }

    /**
     * @param string $currency
     */
    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return int
     */
    public function getPlayerAchievements(): int
    {
        return $this->playerAchievements ?: 0;
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
        return $this->overallAchievements?: 0;
    }

    /**
     * @param int $overallAchievements
     */
    public function setOverallAchievements(int $overallAchievements): void
    {
        $this->overallAchievements = $overallAchievements;
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
     * @return Purchase[]
     */
    public function getPurchases(): array
    {
        return $this->purchases->toArray();
    }

    /**
     * @return Purchase|null
     */
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
     * @return bool
     */
    public function hasGamePurchase(): bool
    {
        $purchases = $this->getPurchases();

        foreach ($purchases as $purchase) {
            if ($purchase->getType() == Purchase::GAME_PURCHASE) {
                return true;
            }
        }

        return false;
    }

    public function setSlug(): void
    {
        $this->slug = $this->slugify($this->getName());
    }
}
