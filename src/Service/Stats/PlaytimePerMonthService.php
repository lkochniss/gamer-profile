<?php

namespace App\Service\Stats;

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
        $playtimePerMonth = $this->getPlaytimePerMonth();

        $playtimePerMonth->addToDuration($gameSession->getDuration());
        $playtimePerMonth->addSession();

        $this->playtimePerMonthRepository->save($playtimePerMonth);

        return $playtimePerMonth;
    }

    /**
     * @param int $diff
     * @return PlaytimePerMonth
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateSession(int $diff): PlaytimePerMonth
    {
        $playtimePerMonth = $this->getPlaytimePerMonth();

        $playtimePerMonth->addToDuration($diff);

        $this->playtimePerMonthRepository->save($playtimePerMonth);

        return $playtimePerMonth;
    }

    /**
     * @return PlaytimePerMonth
     */
    private function getPlaytimePerMonth(): PlaytimePerMonth
    {
        $month = new \DateTime('first day of this month 00:00:00');
        $playtimePerMonth = $this->playtimePerMonthRepository->findOneBy([
            'month' => $month
        ]);

        if (is_null($playtimePerMonth)) {
            $playtimePerMonth = new PlaytimePerMonth($month);
        }

        return $playtimePerMonth;
    }
}
