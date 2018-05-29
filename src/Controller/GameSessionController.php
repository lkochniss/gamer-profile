<?php

namespace App\Controller;

use App\Repository\GameRepository;
use App\Repository\GameSessionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SessionController
 */
class GameSessionController extends Controller
{
    /**
     * @param GameSessionRepository $gameSessionRepository
     * @return Response
     */
    public function listBackend(GameSessionRepository $gameSessionRepository): Response
    {
        $entities = $gameSessionRepository->findAll();
        return $this->render(
           'GameSession/list-backend.html.twig',
            [
                'entities' => $entities,
            ]
        );
    }

    /**
     * @param int $id
     * @param GameRepository $gameRepository
     * @param GameSessionRepository $gameSessionRepository
     * @return Response
     *
     * @SuppressWarnings(PHPMD.ShortVariableName)
     */
    public function listBackendForGame(
        int $id,
        GameRepository $gameRepository,
        GameSessionRepository $gameSessionRepository
    ): Response {
        $game = $gameRepository->find($id);
        $entities = $gameSessionRepository->findBy(['game' => $id]);

        return $this->render(
            sprintf('%s/list-backend-for-game.html.twig', $this->getTemplateBasePath()),
            [
                'entities' => $entities,
                'game' => $game
            ]
        );
    }
}
