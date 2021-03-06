<?php

namespace App\Controller;

use App\Repository\GameRepository;
use App\Repository\GameSessionRepository;
use App\Repository\GameSessionsPerMonthRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class SessionController
 */
class GameSessionController extends AbstractController
{
    /**
     * @param GameSessionRepository $gameSessionRepository
     * @param UserInterface $user
     * @return Response
     */
    public function list(GameSessionRepository $gameSessionRepository, UserInterface $user): Response
    {
        $entities = $gameSessionRepository->findBy(['steamUserId' => $user->getSteamId()]);
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
        $entities = $gameSessionRepository->findBy(['game' => $id, 'steamUserId' => $user->getSteamId()]);

        return $this->render(
            'GameSession/list-for-game.html.twig',
            [
                'entities' => $entities,
                'game' => $game
            ]
        );
    }

    /**
     * @param GameSessionsPerMonthRepository $gameSessionsPerMonthRepository
     * @param UserInterface $user
     * @return Response
     */
    public function listPerMonth(
        GameSessionsPerMonthRepository $gameSessionsPerMonthRepository,
        UserInterface $user
    ): Response {
        $gamesPerMonth = $gameSessionsPerMonthRepository->findBy(['steamUserId' => $user->getSteamId()]);

        return $this->render(
            'GameSession/list-per-month.html.twig',
            [
                'entities' => $gamesPerMonth,
            ]
        );
    }
}
