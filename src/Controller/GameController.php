<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\Type\GameType;
use App\Service\Steam\Entity\UpdateGameInformationService;

/**
 * Class GameController
 */
class GameController extends AbstractCrudController
{
    /**
     * @param int $id
     * @param UpdateGameInformationService $updateGameInformationService
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(int $id, UpdateGameInformationService $updateGameInformationService)
    {
        $updateGameInformationService->updateGameInformationForSteamAppId($id);

        return $this->redirect($this->generateUrl('game_edit', ['id' => $id]));
    }

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
