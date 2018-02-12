<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\Type\GameType;
use App\Repository\GameRepository;
use App\Service\Steam\Entity\UpdateGameInformationService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class GameController
 */
class GameController extends AbstractCrudController
{
    /**
     * @param int $id
     * @param GameRepository $gameRepository
     * @param UpdateGameInformationService $updateGameInformationService
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(
        int $id,
        GameRepository $gameRepository,
        UpdateGameInformationService $updateGameInformationService
    ) {
        $game = $gameRepository->find($id);
        $updateGameInformationService->updateGameInformationForSteamAppId($game->getSteamAppId());

        return $this->redirect($this->generateUrl('game_dashboard', ['id' => $id]));
    }

    /**
     * @param int $id
     * @return Response
     */
    public function dashboard(int $id): Response
    {
        $entity = $this->getDoctrine()->getRepository($this->getEntityName())->find($id);
        if (is_null($entity)) {
            throw new NotFoundHttpException();
        }

        return $this->render(#
            sprintf('%s/dashboard.html.twig', $this->getTemplateBasePath()),
            [
                'entity' => $entity
            ]
        );
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
