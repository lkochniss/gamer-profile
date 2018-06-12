<?php

namespace App\Service\Entity;

use App\Entity\Game;
use App\Repository\GameRepository;
use App\Service\Transformation\GameInformationService;
use App\Service\Transformation\GameUserInformationService;

/**
 * Class CreateNewGameService
 */
class CreateNewGameService
{
    /**
     * @var GameUserInformationService
     */
    private $userInformationService;

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
     * @param GameUserInformationService $userInformationService
     * @param GameInformationService $gameInformationService
     * @param GameRepository $gameRepository
     */
    public function __construct(
        GameUserInformationService $userInformationService,
        GameInformationService $gameInformationService,
        GameRepository $gameRepository
    ) {
        $this->userInformationService = $userInformationService;
        $this->gameInformationService = $gameInformationService;
        $this->gameRepository = $gameRepository;
    }

    /**
     * @param int $steamAppId
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Nette\Utils\JsonException
     */
    public function createGameIfNotExist(int $steamAppId): string
    {
        $game = $this->gameRepository->findOneBySteamAppId($steamAppId);

        if ($game !== null) {
            return 'S';
        }

        $game = new Game();
        $game->setSteamAppId($steamAppId);
        $game->setModifiedAt();

        $game = $this->gameInformationService->addToGame($game);
        if ($game == null) {
            return 'F';
        }

        $game = $this->userInformationService->addPlaytime($game);
        if ($game == null) {
            return 'F';
        }

        $game = $this->userInformationService->addAchievements($game);
        if ($game == null) {
            return 'F';
        }

        $this->gameRepository->save($game);

        return 'N';
    }
}
