<?php

namespace App\Controller;

use App\Repository\GameRepository;
use App\Repository\GameSessionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class SessionController
 */
class GameSessionController extends Controller
{
    /**
     * @param GameSessionRepository $gameSessionRepository
     * @param UserInterface $user
     * @return Response
     */
    public function list(GameSessionRepository $gameSessionRepository, UserInterface $user): Response
    {
        $entities = $gameSessionRepository->findBy(['user' => $user]);
        return $this->render(
            'GameSession/list.html.twig',
            [
                'entities' => $entities,
            ]
        );
    }

    /**
     * @param int $id
     * @param GameRepository $gameRepository
     * @param GameSessionRepository $gameSessionRepository
     * @param UserInterface $user
     * @return Response
     *
     * @SuppressWarnings(PHPMD.ShortVariableName)
     */
    public function listForGame(
        int $id,
        GameRepository $gameRepository,
        GameSessionRepository $gameSessionRepository,
        UserInterface $user
    ): Response {
        $game = $gameRepository->find($id);
        $entities = $gameSessionRepository->findBy(['game' => $id, 'user' => $user]);

        return $this->render(
            'GameSession/list-for-game.html.twig',
            [
                'entities' => $entities,
                'game' => $game
            ]
        );
    }
}
