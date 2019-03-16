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
     * PlaytimeService constructor.
     * @param GameUserInformationService $gameUserInformationService
     * @param PlaytimeRepository $playtimeRepository
     */
    public function __construct(
        GameUserInformationService $gameUserInformationService,
        PlaytimeRepository $playtimeRepository
    ) {
        $this->gameUserInformationService = $gameUserInformationService;
        $this->playtimeRepository = $playtimeRepository;
    }

    /**
     * @param User $user
     * @param Game $game
     * @return Playtime
     */
    public function create(User $user, Game $game): Playtime
    {
        $playtime = $this->playtimeRepository->findOneBy(['game' => $game, 'user' => $user]);

        if (!is_null($playtime)) {
            return $playtime;
        }

        $gamePlaytime = $this->gameUserInformationService->getPlaytimeForGame(
            $game->getSteamAppId(),
            $user->getSteamId()
        );

        $playtime = new Playtime($user, $game);
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
            $playtime->getUser()->getSteamId()
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
}
