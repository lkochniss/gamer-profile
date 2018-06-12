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
     * @param GameInformationService $writeGameInformationService
     * @param GameRepository $gameRepository
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     */
    public function __construct(
        GameInformationService $writeGameInformationService,
        GameRepository $gameRepository
    ) {
        $this->gameInformationService = $writeGameInformationService;
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

        $game = $this->gameInformationService->addToGame($game);
        if ($game === null) {
            return 'F';
        }

        $this->gameRepository->save($game);

        return 'U';
    }
}
