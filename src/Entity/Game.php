<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Game
 */
class Game extends AbstractEntity
{
    const OPEN = 'open';
    const PAUSED = 'paused';
    const PLAYING = 'playing';
    const FINISHED = 'finished';
    const GIVEN_UP = 'given_up';

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
    private $releaseDate;

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
    private $gameSessions;

    /**
     * @var ArrayCollection
     */
    private $purchases;

    /**
     * @var string
     */
    private $status;

    /**
     * Game constructor.
     */
    public function __construct()
    {
        $this->price = 0;
        $this->currency = 'USD';

        $this->playerAchievements = 0;
        $this->overallAchievements = 0;

        $this->recentlyPlayed = 0;
        $this->timePlayed = 0;

        $this->status = $this::OPEN;

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
    public function getReleaseDate(): \DateTime
    {
        return $this->releaseDate ? $this->releaseDate : new \DateTime();
    }

    /**
     * @param \DateTime $releaseDate
     */
    public function setReleaseDate(\DateTime $releaseDate): void
    {
        $this->releaseDate = $releaseDate;
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
        return getenv('DEFAULT_CURRENCY');
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
     * @param GameSession $gameSession
     */
    public function addGameSession(GameSession $gameSession): void
    {
        if (!$this->gameSessions->contains($gameSession)) {
            $this->gameSessions->add($gameSession);
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

    public function setSlug(): void
    {
        $this->slug = $this->slugify($this->getName());
    }
}
