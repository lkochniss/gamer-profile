<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\Type\GameType;
use App\Repository\GameRepository;
use App\Repository\GameSessionRepository;
use App\Repository\GameSessionsPerMonthRepository;
use App\Service\Entity\UpdateGameInformationService;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     *
     * @SuppressWarnings(PHPMD.ShortVariableName)
     * @SuppressWarnings(PHPMD.LongVariableName)
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
     * @param GameSessionsPerMonthRepository $gameSessionsPerMonthRepository
     * @return Response
     *
     * @SuppressWarnings(PHPMD.ShortVariableName)
     */
    public function dashboard(int $id, GameSessionsPerMonthRepository $gameSessionsPerMonthRepository): Response
    {
        $entity = $this->getGame($id);

        $now = new \DateTime();
        $oldestEntry = $gameSessionsPerMonthRepository->findOneBy([]);
        $yearsWithSessions = [];
        for ($i = $oldestEntry->getMonth()->format('Y'); $i <= $now->format('Y'); $i++) {
            if ($gameSessionsPerMonthRepository->findByYear($i)) {
                $yearsWithSessions[] = $i;
            }
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
     * @param int $id
     * @return RedirectResponse
     *
     * @SuppressWarnings(PHPMD.ShortVariableName)
     */
    public function setStatusOpen(int $id): RedirectResponse
    {
        $entity = $this->getGame($id);
        $entity->setStatusOpen();
        $this->getDoctrine()->getRepository($this->getEntityName())->save($entity);

        return $this->redirectToRoute('game_dashboard', ['id' => $id]);
    }

    /**
     * @param int $id
     * @return RedirectResponse
     *
     * @SuppressWarnings(PHPMD.ShortVariableName)
     */
    public function setStatusPaused(int $id): RedirectResponse
    {
        $entity = $this->getGame($id);
        $entity->setStatusPaused();
        $this->getDoctrine()->getRepository($this->getEntityName())->save($entity);

        return $this->redirectToRoute('game_dashboard', ['id' => $id]);
    }

    /**
     * @param int $id
     * @return RedirectResponse
     *
     * @SuppressWarnings(PHPMD.ShortVariableName)
     */
    public function setStatusPlaying(int $id): RedirectResponse
    {
        $entity = $this->getGame($id);
        $entity->setStatusPlaying();
        $this->getDoctrine()->getRepository($this->getEntityName())->save($entity);

        return $this->redirectToRoute('game_dashboard', ['id' => $id]);
    }

    /**
     * @param int $id
     * @return RedirectResponse
     *
     * @SuppressWarnings(PHPMD.ShortVariableName)
     */
    public function setStatusFinished(int $id): RedirectResponse
    {
        $entity = $this->getGame($id);
        $entity->setStatusFinished();
        $this->getDoctrine()->getRepository($this->getEntityName())->save($entity);

        return $this->redirectToRoute('game_dashboard', ['id' => $id]);
    }

    /**
     * @param int $id
     * @return RedirectResponse
     *
     * @SuppressWarnings(PHPMD.ShortVariableName)
     */
    public function setStatusGivenUp(int $id): RedirectResponse
    {
        $entity = $this->getGame($id);
        $entity->setStatusGivenUp();
        $this->getDoctrine()->getRepository($this->getEntityName())->save($entity);

        return $this->redirectToRoute('game_dashboard', ['id' => $id]);
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
        return '';
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

    private function getGame(int $id)
    {
        $entity = $this->getDoctrine()->getRepository($this->getEntityName())->find($id);
        if (is_null($entity)) {
            throw new NotFoundHttpException();
        }

        return $entity;
    }
}
