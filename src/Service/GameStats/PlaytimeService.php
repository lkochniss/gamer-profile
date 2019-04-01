<?php

namespace App\Service\GameStats;

use App\Entity\Game;
use App\Entity\Playtime;
use App\Entity\User;
use App\Repository\PlaytimeRepository;
use App\Service\Transformation\GameUserInformationService;

class PlaytimeService
{
    /**
     * @var GameUserInformationService
     */
    private $gameUserInformationService;

    /**
     * @var PlaytimeRepository
     */
    private $playtimeRepository;

    /**
     * @var GameSessionService
     */
    private $gameSessionService;

    /**
     * PlaytimeService constructor.
     * @param GameUserInformationService $gameUserInformationService
     * @param PlaytimeRepository $playtimeRepository
     * @param GameSessionService $gameSessionService
     */
    public function __construct(
        GameUserInformationService $gameUserInformationService,
        PlaytimeRepository $playtimeRepository,
        GameSessionService $gameSessionService
    ) {
        $this->gameUserInformationService = $gameUserInformationService;
        $this->playtimeRepository = $playtimeRepository;
        $this->gameSessionService = $gameSessionService;
    }

    /**
     * @param string $steamUserId
     * @param Game $game
     * @return Playtime
     */
    public function create(string $steamUserId, Game $game): Playtime
    {
        $playtime = $this->playtimeRepository->findOneBy(['game' => $game, 'steamUserId' => $steamUserId]);

        if (!is_null($playtime)) {
            return $playtime;
        }

        $gamePlaytime = $this->gameUserInformationService->getPlaytimeForGame(
            $game->getSteamAppId(),
            $steamUserId
        );

        $playtime = new Playtime($steamUserId, $game);
        $playtime->setOverallPlaytime($gamePlaytime->getOverallPlaytime());
        $playtime->setRecentPlaytime($gamePlaytime->getRecentPlaytime());

        try {
            $this->playtimeRepository->save($playtime);
        } catch (\Doctrine\ORM\OptimisticLockException $optimisticLockException) {
        } catch (\Doctrine\ORM\ORMException $exception) {
        }

        return $playtime;
    }

    /**
     * @param Playtime $playtime
     * @return Playtime
     */
    public function update(Playtime $playtime): Playtime
    {
        $gamePlaytime = $this->gameUserInformationService->getPlaytimeForGame(
            $playtime->getGame()->getSteamAppId(),
            $playtime->getSteamUserId()
        );


        $gameSession = $this->gameSessionService->getTodaysGameSession(
            $playtime->getSteamUserId(),
            $playtime->getGame()
        );
        $this->gameSessionService->updateGameSession(
            $gameSession,
            $playtime->getOverallPlaytime(),
            $gamePlaytime->getOverallPlaytime()
        );

        $playtime->setOverallPlaytime($gamePlaytime->getOverallPlaytime());
        $playtime->setRecentPlaytime($gamePlaytime->getRecentPlaytime());

        try {
            $this->playtimeRepository->save($playtime);
        } catch (\Doctrine\ORM\OptimisticLockException $optimisticLockException) {
        } catch (\Doctrine\ORM\ORMException $exception) {
        }

        return $playtime;
    }

    /**
     * @param Game $game
     * @param User $user
     */
    public function updateGameForUser(Game $game, User $user): void
    {
        $playtime = $this->playtimeRepository->findOneBy(['game' => $game, 'user' => $user]);

        if (!is_null($playtime)) {
            $this->update($playtime);
        }
    }

    /**
     * @param Playtime $playtime
     */
    public function resetRecentPlaytime(Playtime $playtime): void
    {
        $playtime->setRecentPlaytime(0);
        try {
            $this->playtimeRepository->save($playtime);
        } catch (\Doctrine\ORM\OptimisticLockException $optimisticLockException) {
        } catch (\Doctrine\ORM\ORMException $exception) {
        }
    }

    /**
     * @param User $user
     */
    public function resetRecentPlaytimeForUser(User $user)
    {
        $playtimes = $this->playtimeRepository->getRecentPlaytime($user);

        foreach ($playtimes as $playtime) {
            $this->resetRecentPlaytime($playtime);
        }
    }
}
