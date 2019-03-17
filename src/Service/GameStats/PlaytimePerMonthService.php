<?php

namespace App\Service\GameStats;

use App\Entity\GameSession;
use App\Entity\PlaytimePerMonth;
use App\Entity\User;
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
        $playtimePerMonth = $this->getPlaytimePerMonth($gameSession->getUser());

        $playtimePerMonth->addToDuration($gameSession->getDuration());
        $playtimePerMonth->addSession();

        $this->playtimePerMonthRepository->save($playtimePerMonth);

        return $playtimePerMonth;
    }

    /**
     * @param int $diff
     * @param User $user
     * @return PlaytimePerMonth
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateSession(int $diff, User $user): PlaytimePerMonth
    {
        $playtimePerMonth = $this->getPlaytimePerMonth($user);

        $playtimePerMonth->addToDuration($diff);

        $this->playtimePerMonthRepository->save($playtimePerMonth);

        return $playtimePerMonth;
    }

    /**
     * @param User $user
     * @return PlaytimePerMonth
     */
    private function getPlaytimePerMonth(User $user): PlaytimePerMonth
    {
        $month = new \DateTime('first day of this month 00:00:00');
        $playtimePerMonth = $this->playtimePerMonthRepository->findOneBy([
            'month' => $month,
            'user' => $user
        ]);

        if (is_null($playtimePerMonth)) {
            $playtimePerMonth = new PlaytimePerMonth($month, $user);
        }

        return $playtimePerMonth;
    }
}
