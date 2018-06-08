<?php

namespace App\Service\Entity;

use App\Entity\Achievements;
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
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     */
    public function __construct(
        GameUserInformationService $gameUserInformationService,
        GameInformationService $gameInformationService,
        GameRepository $gameRepository
    ) {
        $this->gameUserInformationService = $gameUserInformationService;
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

        return $this->createGame($steamAppId);
    }

    /**
     * @param int $steamAppId
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Nette\Utils\JsonException
     */
    private function createGame(int $steamAppId): string
    {
        $gameInformation = $this->gameInformationService->getGameInformationEntityForSteamAppId($steamAppId);
        if ($gameInformation === null) {
            return 'F';
        }

        $userInformation = $this->gameUserInformationService->getUserInformationEntityForSteamAppId($steamAppId);
        if ($userInformation === null) {
            return 'F';
        }

        $game = new Game();
        $game->setSteamAppId($steamAppId);
        $game->setModifiedAt();

        $game->setName($gameInformation->getName());
        $game->setHeaderImagePath($gameInformation->getHeaderImagePath());
        $game->setPrice($gameInformation->getPrice());
        $game->setCurrency($gameInformation->getCurrency());
        $game->setReleaseDate($gameInformation->getReleaseDate());

        $game->setTimePlayed($userInformation->getTimePlayed());
        $game->setRecentlyPlayed($userInformation->getRecentlyPlayed());

        $gameAchievements = $this->gameUserInformationService->getAchievementsForGame($steamAppId);

        if (!empty($gameAchievements) && array_key_exists('achievements', $gameAchievements['playerstats'])) {
            $achievements = new Achievements($gameAchievements);
            $game->setPlayerAchievements($achievements->getPlayerAchievements());
            $game->setOverallAchievements($achievements->getOverallAchievements());
        }

        $this->gameRepository->save($game);

        return 'N';
    }
}
