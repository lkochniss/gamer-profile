<?php

namespace App\Listener;

use App\Entity\Game;
use App\Entity\GameSession;
use App\Entity\GameSessionsPerMonth;
use App\Entity\OverallGameStats;
use App\Repository\GameSessionsPerMonthRepository;
use App\Repository\OverallGameStatsRepository;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Class GameSessionListener
 */
class GameSessionsPerMonthListener
{


    /**
     * @param LifecycleEventArgs $args
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postPersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if ($entity instanceof GameSession === false) {
            return;
        }

        /**
         * @var OverallGameStatsRepository $overallGameStatsRepository
         */
        $overallGameStatsRepository = $args->getEntityManager()->getRepository(OverallGameStats::class);
        $overallGameStats = $this->getOverallGameStats($overallGameStatsRepository);

        $overallGameStats->addToGameSessions(1);
        $overallGameStatsRepository->save($overallGameStats);

        $gameSessionPerMonthRepository = $args->getEntityManager()->getRepository(GameSessionsPerMonth::class);
        $gameSessionPerMonth = $this->getGameSessionsPerMonth($gameSessionPerMonthRepository, $entity->getGame());

        $gameSessionPerMonth->addToDuration($entity->getDuration());

        $gameSessionPerMonthRepository->save($gameSessionPerMonth);
    }

    /**
     * @param LifecycleEventArgs $args
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if ($entity instanceof GameSession === false) {
            return;
        }

        $gameSessionPerMonthRepository = $args->getEntityManager()->getRepository(GameSessionsPerMonth::class);
        $gameSessionPerMonth = $this->getGameSessionsPerMonth($gameSessionPerMonthRepository, $entity->getGame());

        $unitOfWork = $args->getEntityManager()->getUnitOfWork();
        $changeSet = $unitOfWork->getEntityChangeSet($entity);

        if (array_key_exists('duration', $changeSet)) {
            $diff = $changeSet['duration'][1] - $changeSet['duration'][0];
            $gameSessionPerMonth->addToDuration($diff);
        }

        $gameSessionPerMonth->addToDuration($entity->getDuration());

        $gameSessionPerMonthRepository->save($gameSessionPerMonth);
    }

    /**
     * @param OverallGameStatsRepository $overallGameStatsRepository
     * @return OverallGameStats
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function getOverallGameStats(OverallGameStatsRepository $overallGameStatsRepository): OverallGameStats
    {
        $overallGameStats = $overallGameStatsRepository->findOneByIdentifier(getenv('STEAM_USER_ID'));

        if (is_null($overallGameStats)) {
            $overallGameStats = new OverallGameStats();
            $overallGameStatsRepository->save($overallGameStats);
        }

        return $overallGameStats;
    }

    /**
     * @param GameSessionsPerMonthRepository $gameSessionsPerMonthRepository
     * @param Game $game
     * @return GameSessionsPerMonth
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function getGameSessionsPerMonth(
        GameSessionsPerMonthRepository $gameSessionsPerMonthRepository,
        Game $game
    ): GameSessionsPerMonth {
        $month = new \DateTime('first day of this month 00:00:00');
        $gameSessionsPerMonth = $gameSessionsPerMonthRepository->findOneBy([
            'game' => $game,
            'month' => $month
        ]);

        if (is_null($gameSessionsPerMonth)) {
            $gameSessionsPerMonth = new GameSessionsPerMonth($month, $game);
            $gameSessionsPerMonthRepository->save($gameSessionsPerMonth);
        }

        return $gameSessionsPerMonth;
    }
}
