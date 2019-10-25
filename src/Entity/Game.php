<?php

namespace App\Entity;

/**
 * Class Game
 */
class Game extends AbstractEntity
{
    const NAME_FAILED = 'unknown game';
    const IMAGE_FAILED = '';
    const CATEGORIES_FAILED = '[{}]';

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
    private $headerImagePath;

    /**
     * @var string
     */
    private $categories;

    /**
     * Game constructor.
     * @param int $steamAppId
     */
    public function __construct(int $steamAppId)
    {
        $this->steamAppId = $steamAppId;
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
     * @return array
     */
    public function getCategories(): array
    {
        return json_decode($this->categories, true) ?: [];
    }

    /**
     * @return bool
     */
    public function hasSinglePlayer(): bool
    {
        foreach ($this->getCategories() as $category) {
            if (array_key_exists('id', $category) && $category['id'] === 2) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function hasMultiPlayer(): bool
    {
        foreach ($this->getCategories() as $category) {
            if (array_key_exists('id', $category) && ($category['id'] === 1 || $category['id'] === 36)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function hasCoOp(): bool
    {
        foreach ($this->getCategories() as $category) {
            if (array_key_exists('id', $category) && ($category['id'] === 9 || $category['id'] === 38)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function hasRemotePlayTogether(): bool
    {
        foreach ($this->getCategories() as $category) {
            if (array_key_exists('id', $category) && $category['id'] === 40) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $categories
     */
    public function setCategories(string $categories): void
    {
        $this->categories = $categories;
    }
}
