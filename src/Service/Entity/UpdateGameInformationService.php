<?php

namespace App\Service\Entity;

use App\Repository\GameRepository;
use App\Service\Transformation\GameInformationService;

/**
 * Class UpdateGameInformationService
 */
class UpdateGameInformationService
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
            return 'F';
        }

        $gameInformation = $this->gameInformationService->getGameInformationEntityForSteamAppId($steamAppId);

        if ($gameInformation === null) {
            return 'F';
        }

        $game->setName($gameInformation->getName());
        $game->setHeaderImagePath($gameInformation->getHeaderImagePath());
        $game->setPrice($gameInformation->getPrice());
        $game->setCurrency($gameInformation->getCurrency());
        $game->setReleaseDate($gameInformation->getReleaseDate());

        $this->gameRepository->save($game);

        return 'U';
    }
}
