<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\Type\GameType;

/**
 * Class GameController
 */
class GameController extends AbstractCrudController
{

    /**
     * @return Game
     */
    protected function createNewEntity()
    {
        return new Game();
    }

    /**
     * @return string
     */
    protected function getFormType(): string
    {
        return GameType::class;
    }

    /**
     * @return string
     */
    protected function getTemplateBasePath(): string
    {
        return 'Game';
    }

    /**
     * @return string
     */
    protected function getEntityName(): string
    {
        return 'App\Entity\Game';
    }

    /**
     * @return string
     */
    protected function getRoutePrefix(): string
    {
        return 'game';
    }
}
