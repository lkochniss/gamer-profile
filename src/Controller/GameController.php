<?php

namespace App\Controller;

use App\Entity\GameStats;
use App\Repository\GameSessionsPerMonthRepository;
use App\Repository\GameStatsRepository;
use App\Service\Steam\CompareGamesForUserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class GameController
 */
class GameController extends AbstractController
{
    /**
     * @param int $id
     * @param GameStatsRepository $gameStatsRepository
     * @param UserInterface $user
     * @param GameSessionsPerMonthRepository $gameSessionsPerMonthRepository
     * @return Response
     *
     * @SuppressWarnings(PHPMD.ShortVariableName)
     */
    public function dashboard(
        int $id,
        GameStatsRepository $gameStatsRepository,
        UserInterface $user,
        GameSessionsPerMonthRepository $gameSessionsPerMonthRepository
    ): Response {
        $game = $this->getDoctrine()->getRepository($this->getEntityName())->find($id);
        if (is_null($game)) {
            throw new NotFoundHttpException();
        }

        $entity = $gameStatsRepository->findOneBy(['game' => $game, 'steamUserId' => $user->getSteamId()]);

        if (is_null($entity)) {
            throw new NotFoundHttpException();
        }

        $now = new \DateTime();
        $oldestEntry = $gameSessionsPerMonthRepository->findOneBy([]);
        $yearsWithSessions = [];

        if ($oldestEntry) {
            for ($i = $oldestEntry->getMonth()->format('Y'); $i <= $now->format('Y'); $i++) {
                if ($gameSessionsPerMonthRepository->findByYear($i, $user)) {
                    $yearsWithSessions[] = $i;
                }
            }
        }

        if (empty($yearsWithSessions)) {
            $yearsWithSessions[] = $now->format('Y');
        }

        return $this->render(
            sprintf('%s/dashboard.html.twig', $this->getTemplateBasePath()),
            [
                'entity' => $entity,
                'yearsWithSessions' => $yearsWithSessions,
                'currentYear' => $now->format('Y')
            ]
        );
    }

    /**
     * @param UserInterface $user
     * @return Response
     */
    public function list(UserInterface $user): Response
    {
        $entities = $this->getDoctrine()->getRepository(GameStats::class)->findBy([
            'steamUserId' => $user->getSteamId()
        ]);

        return $this->render(
            sprintf('%s/list.html.twig', $this->getTemplateBasePath()),
            [
                'entities' => $entities,
            ]
        );
    }

    /**
     * @param Request $request
     * @param UserInterface $user
     * @param CompareGamesForUserService $service
     * @return Response
     */
    public function compare(
        Request $request,
        UserInterface $user,
        CompareGamesForUserService $service
    ): Response {
        $friendsSteamUserId = $request->get('steamUserId') ?: null;

        $games = [];
        if ($friendsSteamUserId !== null) {
            $games = $service->compareMyGamesWithFriend($user->getSteamId(), $friendsSteamUserId);
        }

        return $this->render(
            sprintf('%s/compare.html.twig', $this->getTemplateBasePath()),
            [
                'games' => $games,
                'friendsSteamUserId' => $friendsSteamUserId
            ]
        );
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
