<?php


namespace App\Controller;

use App\Entity\Game;
use App\Entity\GameStats;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\User\UserInterface;

class GameStatsController extends AbstractController
{
    /**
     * @param int $id
     * @param UserInterface $user
     * @return RedirectResponse
     */
    public function setStatusOpen(int $id, UserInterface $user): RedirectResponse
    {
        $entity = $this->getGameStats($id, $user);
        $entity->setStatusOpen();
        $this->getDoctrine()->getRepository($this->getEntityName())->save($entity);
        return $this->redirectToRoute('game_dashboard', ['id' => $id]);
    }

    /**
     * @param int $id
     * @param UserInterface $user
     * @return RedirectResponse
     */
    public function setStatusPaused(int $id, UserInterface $user): RedirectResponse
    {
        $entity = $this->getGameStats($id, $user);
        $entity->setStatusPaused();
        $this->getDoctrine()->getRepository($this->getEntityName())->save($entity);
        return $this->redirectToRoute('game_dashboard', ['id' => $id]);
    }

    /**
     * @param int $id
     * @param UserInterface $user
     * @return RedirectResponse
     */
    public function setStatusPlaying(int $id, UserInterface $user): RedirectResponse
    {
        $entity = $this->getGameStats($id, $user);
        $entity->setStatusPlaying();
        $this->getDoctrine()->getRepository($this->getEntityName())->save($entity);
        return $this->redirectToRoute('game_dashboard', ['id' => $id]);
    }

    /**
     * @param int $id
     * @param UserInterface $user
     * @return RedirectResponse
     */
    public function setStatusFinished(int $id, UserInterface $user): RedirectResponse
    {
        $entity = $this->getGameStats($id, $user);
        $entity->setStatusFinished();
        $this->getDoctrine()->getRepository($this->getEntityName())->save($entity);
        return $this->redirectToRoute('game_dashboard', ['id' => $id]);
    }

    /**
     * @param int $id
     * @param UserInterface $user
     * @return RedirectResponse
     */
    public function setStatusGivenUp(int $id, UserInterface $user): RedirectResponse
    {
        $entity = $this->getGameStats($id, $user);
        $entity->setStatusGivenUp();
        $this->getDoctrine()->getRepository($this->getEntityName())->save($entity);
        return $this->redirectToRoute('game_dashboard', ['id' => $id]);
    }

    private function getGameStats(int $id, UserInterface $user)
    {
        $game = $this->getDoctrine()->getRepository(Game::class)->find($id);
        $entity = $this->getDoctrine()->getRepository($this->getEntityName())->findOneBy([
            'game' => $game,
            'steamUserId' => $user->getSteamId()
        ]);

        if (is_null($entity)) {
            throw new NotFoundHttpException();
        }

        return $entity;
    }

    /**
     * @return string
     */
    private function getEntityName(): string
    {
        return GameStats::class;
    }
}
