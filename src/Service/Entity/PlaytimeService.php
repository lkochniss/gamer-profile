<?php

namespace App\Service\Entity;

use App\Entity\Game;
use App\Entity\Playtime;
use App\Entity\User;
use App\Repository\PlaytimeRepository;
use App\Service\Transformation\GameUserInformationService;

/**
 * Class PlaytimeService
 */
class PlaytimeService
{
    /**
     * @var GameUserInformationService
     */
    private $userInformationService;

    /**
     * @var PlaytimeRepository
     */
    private $playtimeRepository;

    /**
     * @var SessionService
     */
    private $sessionService;

    /**
     * UpdatePlaytimeService constructor.
     * @param GameUserInformationService $userInformationService
     * @param PlaytimeRepository $playtimeRepository
     * @param SessionService $sessionService
     */
    public function __construct(
        GameUserInformationService $userInformationService,
        PlaytimeRepository $playtimeRepository,
        SessionService $sessionService
    ) {
        $this->userInformationService = $userInformationService;
        $this->playtimeRepository = $playtimeRepository;
        $this->sessionService = $sessionService;
    }

    /**
     * @param Game $game
     * @param User $user
     * @return Playtime
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Nette\Utils\JsonException
     */
    public function createIfNotExists(Game $game, User $user): Playtime
    {
        $playtime = $this->playtimeRepository->findOneBy(['game' => $game, 'user' => $user]);

        if (!is_null($playtime)) {
            return $playtime;
        }

        $updatedInformation = $this->userInformationService->getUserInformationEntityForSteamAppId(
            $game->getSteamAppId(),
            $user->getSteamId()
        );

        $playtime = new Playtime($user, $game);
        $playtime->setRecentPlaytime($updatedInformation->getRecentPlaytime());
        $playtime->setOverallPlaytime($updatedInformation->getOverallPlaytime());

        $this->playtimeRepository->save($playtime);

        return $playtime;
    }

    /**
     * @param Playtime $playtime
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Nette\Utils\JsonException
     */
    public function update(Playtime $playtime): string
    {
        $updatedInformation = $this->userInformationService->getUserInformationEntityForSteamAppId(
            $playtime->getGame()->getSteamAppId(),
            $playtime->getUser()->getSteamId()
        );

        $this->sessionService->createOrUpdate($playtime, $updatedInformation);

        $playtime->setRecentPlaytime($updatedInformation->getRecentPlaytime());
        $playtime->setOverallPlaytime($updatedInformation->getOverallPlaytime());

        $this->playtimeRepository->save($playtime);

        return 'U';
    }
}
