<?php

namespace App\Service\Steam\Entity;

use App\Repository\GameRepository;
use App\Service\ReportService;
use App\Service\Steam\Transformation\GameUserInformationService;

/**
 * Class UpdateUserInformationService
 */
class UpdateUserInformationService extends ReportService
{
    /**
     * @var GameUserInformationService
     */
    private $ownedGamesService;

    /**
     * @var GameRepository
     */
    private $gameRepository;

    /**
     * UpdateUserInformationService constructor.
     * @param GameUserInformationService $ownedGamesService
     * @param GameRepository $gameRepository
     */
    public function __construct(GameUserInformationService $ownedGamesService, GameRepository $gameRepository)
    {
        $this->ownedGamesService = $ownedGamesService;
        $this->gameRepository = $gameRepository;
    }

    /**
     * @param int $steamAppId
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateUserInformationForSteamAppId(int $steamAppId): string
    {
        $game = $this->gameRepository->findOneBySteamAppId($steamAppId);

        if ($game === null) {
            $this->addEntryToList($steamAppId, ReportService::GAME_NOT_FOUND_ERROR);
            return 'F';
        }

        $userInformation = $this->ownedGamesService->getUserInformationEntityForSteamAppId($steamAppId);

        if ($userInformation === null) {
            $this->addEntryToList($steamAppId, ReportService::FIND_USER_INFORMATION_ERROR);
            return 'F';
        }

        $game->setTimePlayed($userInformation->getTimePlayed());
        $game->setRecentlyPlayed($userInformation->getRecentlyPlayed());

        $this->addEntryToList($game->getName(), ReportService::UPDATED_GAME_USER_INFORMATION);

        $this->gameRepository->save($game);

        return 'U';
    }
}
