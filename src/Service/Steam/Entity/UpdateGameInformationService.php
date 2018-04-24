<?php

namespace App\Service\Steam\Entity;

use App\Repository\GameRepository;
use App\Service\ReportService;
use App\Service\Steam\Transformation\GameInformationService;

/**
 * Class UpdateGameInformationService
 */
class UpdateGameInformationService extends ReportService
{
    /**
     * @var GameInformationService
     */
    private $gameInformationService;

    /**
     * @var GameRepository
     */
    private $gameRepository;

    /**
     * UpdateGameInformationService constructor.
     * @param $gameInformationService
     * @param $gameRepository
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     */
    public function __construct(GameInformationService $gameInformationService, GameRepository $gameRepository)
    {
        $this->gameInformationService = $gameInformationService;
        $this->gameRepository = $gameRepository;
    }

    /**
     * @param int $steamAppId
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateGameInformationForSteamAppId(int $steamAppId): string
    {
        $game = $this->gameRepository->findOneBySteamAppId($steamAppId);

        if ($game === null) {
            $this->addEntryToList($steamAppId, ReportService::GAME_NOT_FOUND_ERROR);
            return 'F';
        }

        $gameInformation = $this->gameInformationService->getGameInformationEntityForSteamAppId($steamAppId);

        if ($gameInformation === null) {
            $this->addEntryToList($steamAppId, ReportService::FIND_GAME_INFORMATION_ERROR);
            return 'F';
        }

        $game->setName($gameInformation->getName());
        $game->setHeaderImagePath($gameInformation->getHeaderImagePath());
        $game->setPrice($gameInformation->getPrice());
        $game->setCurrency($gameInformation->getCurrency());
        $this->addEntryToList($game->getName(), ReportService::UPDATED_GAME_INFORMATION);

        $this->gameRepository->save($game);

        return 'U';
    }
}
