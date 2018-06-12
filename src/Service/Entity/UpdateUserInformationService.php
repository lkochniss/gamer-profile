<?php

namespace App\Service\Entity;

use App\Repository\GameRepository;
use App\Service\Transformation\GameUserInformationService;

/**
 * Class UpdateUserInformationService
 */
class UpdateUserInformationService
{
    /**
     * @var GameUserInformationService
     */
    private $userInformationService;

    /**
     * @var GameRepository
     */
    private $gameRepository;

    /**
     * UpdateUserInformationService constructor.
     * @param GameUserInformationService $gameUserInformationService
     * @param GameRepository $gameRepository
     */
    public function __construct(
        GameUserInformationService $gameUserInformationService,
        GameRepository $gameRepository
    ) {
        $this->userInformationService = $gameUserInformationService;
        $this->gameRepository = $gameRepository;
    }

    /**
     * @param int $steamAppId
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Nette\Utils\JsonException
     */
    public function updateUserInformationForSteamAppId(int $steamAppId): string
    {
        $game = $this->gameRepository->findOneBySteamAppId($steamAppId);

        if ($game === null) {
            return 'F';
        }

        $game = $this->userInformationService->addPlaytime($game);
        if ($game === null) {
            return 'F';
        }
        $this->gameRepository->save($game);

        return 'U';
    }

    /**
     * @param int $steamAppId
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Nette\Utils\JsonException
     */
    public function addSessionForSteamAppId(int $steamAppId): string
    {
        $game = $this->gameRepository->findOneBySteamAppId($steamAppId);

        if ($game === null) {
            return 'F';
        }

        $game = $this->userInformationService->addSession($game);
        $this->gameRepository->save($game);

        return 'N';
    }

    /**
     * @param int $steamAppId
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateAchievementsForSteamAppId(int $steamAppId): string
    {
        $game = $this->gameRepository->findOneBySteamAppId($steamAppId);
        if ($game === null) {
            return 'F';
        }

        $game = $this->userInformationService->addAchievements($game);
        $this->gameRepository->save($game);

        return 'U';
    }
}
