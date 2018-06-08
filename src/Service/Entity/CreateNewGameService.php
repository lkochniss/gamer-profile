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
     * @param GameUserInformationService $writeGameUserInformationService
     * @param GameInformationService $writeGameInformationService
     * @param GameRepository $gameRepository
     */
    public function __construct(
        GameUserInformationService $writeGameUserInformationService,
        GameInformationService $writeGameInformationService,
        GameRepository $gameRepository
    ) {
        $this->userInformationService = $writeGameUserInformationService;
        $this->gameInformationService = $writeGameInformationService;
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
        $game = $this->userInformationService->addPlaytime($game);
        $game = $this->userInformationService->addAchievements($game);

        $this->gameRepository->save($game);

        return 'N';
    }
}
