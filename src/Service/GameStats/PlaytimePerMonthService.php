<?php

namespace App\Service\GameStats;

use App\Entity\GameSession;
use App\Entity\PlaytimePerMonth;
use App\Repository\PlaytimePerMonthRepository;

/**
 * Class PlaytimePerMonthService
 */
class PlaytimePerMonthService
{
    /**
     * @var PlaytimePerMonthRepository
     */
    private $playtimePerMonthRepository;

    /**
     * PlaytimePerMonthService constructor.
     * @param PlaytimePerMonthRepository $playtimePerMonthRepository
     */
    public function __construct(PlaytimePerMonthRepository $playtimePerMonthRepository)
    {
        $this->playtimePerMonthRepository = $playtimePerMonthRepository;
    }

    /**
     * @param GameSession $gameSession
     * @return PlaytimePerMonth
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addSession(GameSession $gameSession): PlaytimePerMonth
    {
        $playtimePerMonth = $this->getPlaytimePerMonth($gameSession->getSteamUserId());

        $playtimePerMonth->addToDuration($gameSession->getDuration());
        $playtimePerMonth->addSession();

        $this->playtimePerMonthRepository->save($playtimePerMonth);

        return $playtimePerMonth;
    }

    /**
     * @param int $diff
     * @param int $steamUserId
     * @return PlaytimePerMonth
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateSession(int $diff, int $steamUserId): PlaytimePerMonth
    {
        $playtimePerMonth = $this->getPlaytimePerMonth($steamUserId);

        $playtimePerMonth->addToDuration($diff);

        $this->playtimePerMonthRepository->save($playtimePerMonth);

        return $playtimePerMonth;
    }

    /**
     * @param int $steamUserId
     * @return PlaytimePerMonth
     */
    private function getPlaytimePerMonth(int $steamUserId): PlaytimePerMonth
    {
        $month = new \DateTime('first day of this month 00:00:00');
        $playtimePerMonth = $this->playtimePerMonthRepository->findOneBy([
            'month' => $month,
            'steamUserId' => $steamUserId
        ]);

        if (is_null($playtimePerMonth)) {
            $playtimePerMonth = new PlaytimePerMonth($month, $steamUserId);
        }

        return $playtimePerMonth;
    }
}
