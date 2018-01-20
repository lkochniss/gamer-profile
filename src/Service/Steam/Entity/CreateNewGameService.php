<?php

namespace App\Service\Steam\Entity;

use App\Entity\Game;
use App\Repository\GameRepository;
use App\Service\ReportService;
use App\Service\Steam\Transformation\GameInformationService;
use App\Service\Steam\Transformation\GameUserInformationService;

/**
 * Class CreateNewGameService
 */
class CreateNewGameService extends ReportService
{
    /**
     * @var GameUserInformationService
     */
    private $gameUserInformationService;

    /**
     * @var GameInformationService
     */
    private $gameInformationService;

    /**
     * @var GameRepository
     */
    private $gameRepository;

    /**
     * CreateNewGameService constructor.
     * @param GameUserInformationService $gameUserInformationService
     * @param GameInformationService $gameInformationService
     * @param GameRepository $gameRepository
     */
    public function __construct(GameUserInformationService $gameUserInformationService, GameInformationService $gameInformationService, GameRepository $gameRepository)
    {
        $this->gameUserInformationService = $gameUserInformationService;
        $this->gameInformationService = $gameInformationService;
        $this->gameRepository = $gameRepository;
    }

    /**
     * @param int $steamAppId
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createGameIfNotExist(int $steamAppId): string
    {
        $game = $this->gameRepository->findOneBySteamAppId($steamAppId);

        if ($game !== null) {
            $this->addEntryToList($game->getName(), ReportService::SKIPPED_GAME);

            return 'S';
        }

        return $this->createGame($steamAppId);
    }

    /**
     * @param int $steamAppId
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function createGame(int $steamAppId): string
    {
        $gameInformation = $this->gameInformationService->getGameInformationEntityForSteamAppId($steamAppId);
        if ($gameInformation === null) {
            $this->addEntryToList($steamAppId, ReportService::FIND_GAME_INFORMATION_ERROR);
            return 'F';
        }

        $userInformation = $this->gameUserInformationService->getUserInformationEntityForSteamAppId($steamAppId);
        if ($userInformation === null) {
            $this->addEntryToList($steamAppId, ReportService::FIND_USER_INFORMATION_ERROR);
            return 'F';
        }

        $game = new Game();
        $game->setSteamAppId($steamAppId);
        $game->setModifiedAt();

        $game->setName($gameInformation->getName());
        $game->setHeaderImagePath($gameInformation->getHeaderImagePath());
        $game->setPrice($gameInformation->getPrice());
        $game->setCurrency($gameInformation->getCurrency());

        $game->setTimePlayed($userInformation->getTimePlayed());
        $game->setRecentlyPlayed($userInformation->getRecentlyPlayed());

        $this->addEntryToList($game->getName(), ReportService::NEW_GAME);
        $this->gameRepository->save($game);

        return 'N';
    }
}
