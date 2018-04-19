<?php

namespace App\Service\Steam\Entity;

use App\Entity\Achievements;
use App\Entity\GameSession;
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
    private $gameUserInformationService;

    /**
     * @var GameRepository
     */
    private $gameRepository;

    /**
     * UpdateUserInformationService constructor.
     * @param GameUserInformationService $gameUserInformationService
     * @param GameRepository $gameRepository
     */
    public function __construct(GameUserInformationService $gameUserInformationService, GameRepository $gameRepository)
    {
        $this->gameUserInformationService = $gameUserInformationService;
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
            $this->addEntryToList($steamAppId, ReportService::GAME_NOT_FOUND_ERROR);
            return 'F';
        }

        $userInformation = $this->gameUserInformationService->getUserInformationEntityForSteamAppId($steamAppId);

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
            $this->addEntryToList($steamAppId, ReportService::GAME_NOT_FOUND_ERROR);
            return 'F';
        }

        $userInformation = $this->gameUserInformationService->getUserInformationEntityForSteamAppId($steamAppId);

        if ($userInformation === null) {
            $this->addEntryToList($steamAppId, ReportService::FIND_USER_INFORMATION_ERROR);
            return 'F';
        }

        if ($userInformation->getRecentlyPlayed() > 0 && $userInformation->getTimePlayed() > $game->getTimePlayed()) {
            $gameSession = new GameSession();
            $duration = $userInformation->getTimePlayed() - $game->getTimePlayed();
            $gameSession->setDuration($duration);
            $game->addGameSession($gameSession);
            $this->gameRepository->save($game);
            $this->addEntryToList($game->getName(), ReportService::FIND_USER_INFORMATION_ERROR);

            return 'S';
        }

        return '';
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
            $this->addEntryToList($steamAppId, ReportService::GAME_NOT_FOUND_ERROR);
            return 'F';
        }

        $gameAchievements = $this->gameUserInformationService->getAchievementsForGame($steamAppId);
        if (
            empty($gameAchievements) ||
            array_key_exists('playerstats', $gameAchievements) ||
            array_key_exists('achievements', $gameAchievements['playerstats'])
        ) {
            return 'F';
        }

        $achievements = new Achievements($gameAchievements);
        $game->setPlayerAchievements($achievements->getPlayerAchievements());
        $game->setOverallAchievements($achievements->getOverallAchievements());

        $this->gameRepository->save($game);

        return 'U';
    }
}
