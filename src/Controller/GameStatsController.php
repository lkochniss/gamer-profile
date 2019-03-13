<?php


namespace App\Controller;


use App\Entity\Game;
use App\Entity\GameStats;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GameStatsController extends Controller
{
    /**
     * @param int $id
     * @return RedirectResponse
     *
     * @SuppressWarnings(PHPMD.ShortVariableName)
     */
    public function setStatusOpen(int $id): RedirectResponse
    {
        $entity = $this->getGameStats($id);
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
        $entity = $this->getGameStats($id);
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
        $entity = $this->getGameStats($id);
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
        $entity = $this->getGameStats($id);
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
        $entity = $this->getGameStats($id);
        $entity->setStatusGivenUp();
        $this->getDoctrine()->getRepository($this->getEntityName())->save($entity);
        return $this->redirectToRoute('game_dashboard', ['id' => $id]);
    }

    private function getGameStats(int $id)
    {
        $game = $this->getDoctrine()->getRepository(Game::class)->find($id);
        $entity = $this->getDoctrine()->getRepository($this->getEntityName())->findOneBy(['game' => $game]);
        if (is_null($entity)) {
            throw new NotFoundHttpException();
        }

        return $entity;
    }

    /**
     * @return string
     */
    private function getEntityName(): string {
        return GameStats::class;
    }
}
