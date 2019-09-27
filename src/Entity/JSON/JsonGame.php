<?php

namespace App\Entity\JSON;

use App\Entity\Game;

/**
 * Class Game
 */
class JsonGame
{
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
     * GameInformation constructor.
     * @param array $gameInformation
     */
    public function __construct(array $gameInformation = [])
    {
        $this->name = array_key_exists(
            'name',
            $gameInformation
        ) ? $gameInformation['name'] : Game::NAME_FAILED;

        $this->headerImagePath = array_key_exists(
            'header_image',
            $gameInformation
        ) ? $gameInformation['header_image'] : Game::IMAGE_FAILED;

        $this->categories = array_key_exists(
            'categories',
            $gameInformation
        ) ? json_encode($gameInformation['categories']) : Game::CATEGORIES_FAILED;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getHeaderImagePath(): string
    {
        return $this->headerImagePath;
    }

    /**
     * @return string
     */
    public function getCategories(): string
    {
        return $this->categories;
    }
}
