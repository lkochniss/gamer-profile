<?php

namespace App\Controller;

use App\Entity\GameSession;

/**
 * Class SessionController
 */
class GameSessionController extends AbstractCrudController
{
    /**
     * @return GameSession
     */
    protected function createNewEntity()
    {
        return new GameSession();
    }

    /**
     * @return string
     */
    protected function getFormType(): string
    {
        return '';
    }

    /**
     * @return string
     */
    protected function getTemplateBasePath(): string
    {
        return 'GameSession';
    }

    /**
     * @return string
     */
    protected function getEntityName(): string
    {
        return 'App\Entity\GameSession';
    }

    /**
     * @return string
     */
    protected function getRoutePrefix(): string
    {
        return 'game_session';
    }
}
