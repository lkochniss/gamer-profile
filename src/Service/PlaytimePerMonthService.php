<?php

namespace App\Service;

use App\Entity\GameSession;
use App\Entity\PlaytimePerMonth;
use App\Repository\GameSessionRepository;
use App\Repository\PlaytimePerMonthRepository;

/**
 * Class PlaytimePerMonthService
 */
class PlaytimePerMonthService
{
    /**
     * @var GameSessionRepository
     */
    private $gameSessionRepository;

    /**
     * @var PlaytimePerMonthRepository
     */
    private $playtimePerMonthRepository;

    /**
     * PlaytimePerMonthService constructor.
     * @param GameSessionRepository $gameSessionRepository
     * @param PlaytimePerMonthRepository $playtimePerMonthRepository
     */
    public function __construct(
        GameSessionRepository $gameSessionRepository,
        PlaytimePerMonthRepository $playtimePerMonthRepository
    ) {
        $this->gameSessionRepository = $gameSessionRepository;
        $this->playtimePerMonthRepository = $playtimePerMonthRepository;
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function generatePlaytimePerMonth(): void
    {
        $playtimePerMonth = $this->playtimePerMonthRepository->findAll();
        if (!empty($playtimePerMonth)) {
            return;
        }

        $gameSessions = $this->gameSessionRepository->findAll();

        foreach ($gameSessions as $gameSession) {
            $playtimePerMonth = $this->getPlaytimePerMonth($gameSession);
            $playtimePerMonth->addToDuration($gameSession->getDuration());
            $playtimePerMonth->addSession();

            $this->playtimePerMonthRepository->save($playtimePerMonth);
        }
    }

    /**
     * @param GameSession $gameSession
     * @return PlaytimePerMonth
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function getPlaytimePerMonth(GameSession $gameSession): PlaytimePerMonth
    {
        $month = new \DateTime(sprintf('first day of %s', $gameSession->getCreatedAt()->format('F Y')));
        $playtimePerMonth = $this->playtimePerMonthRepository->findOneBy([
            'month' => $month
        ]);

        if (is_null($playtimePerMonth)) {
            $playtimePerMonth = new PlaytimePerMonth($month);
            $this->playtimePerMonthRepository->save($playtimePerMonth);
        }

        return $playtimePerMonth;
    }
}
