<?php

namespace App\Service\Entity;

use App\Entity\Game;
use App\Repository\GameRepository;
use App\Service\Transformation\GameInformationService;
use App\Service\Transformation\GameUserInformationService;

/**
 * Class GameService
 */
class GameService
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
     * @return null|Game
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createGameIfNotExist(int $steamAppId): ?Game
    {
        $game = $this->gameRepository->findOneBySteamAppId($steamAppId);

        if (!is_null($game)) {
            return $game;
        }

        $game = new Game();
        $game->setSteamAppId($steamAppId);
        $game->setName('unknown game');
        $game->setHeaderImagePath('https://steamcommunity-a.akamaihd.net/public/images/sharedfiles/steam_workshop_default_image.png');
        $game->setModifiedAt();

        $game = $this->gameInformationService->addToGame($game);

        $this->gameRepository->save($game);

        return $game;
    }

    /**
     * @param int $steamAppId
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(int $steamAppId): string
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
