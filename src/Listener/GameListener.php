<?php

namespace App\Listener;

use App\Entity\Game;
use App\Entity\OverallGameStats;
use App\Repository\OverallGameStatsRepository;
use App\Service\PurchaseService;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Class GameListener
 */
class GameListener
{
    /**
     * @var PurchaseService
     */
    private $purchaseService;

    /**
     * @var array
     */
    private $statProperties = [
        'addToOverallAchievements' => 'overallAchievements',
        'addToPlayerAchievements' => 'playerAchievements',
        'addToRecentlyPlayed' => 'recentlyPlayed',
        'addToTimePlayed' => 'timePlayed'
    ];

    /**
     * GameListener constructor.
     * @param PurchaseService $purchaseService
     */
    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }

    /**
     * @param LifecycleEventArgs $args
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postPersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if ($entity instanceof Game === false) {
            return;
        }

        /**
         * @var OverallGameStatsRepository $overallGameStatsRepository
         */
        $overallGameStatsRepository = $args->getEntityManager()->getRepository(OverallGameStats::class);
        $overallGameStats = $this->getOverallGameStats($overallGameStatsRepository);

        $overallGameStats->addToOverallAchievements($entity->getOverallAchievements());
        $overallGameStats->addToPlayerAchievements($entity->getPlayerAchievements());
        $overallGameStats->addToRecentlyPlayed($entity->getRecentlyPlayed());
        $overallGameStats->addToTimePlayed($entity->getTimePlayed());

        $purchaseMoney = $this->purchaseService->generateOverallCosts($entity);
        $overallGameStats->addToInvestedMoney($this->purchaseService->transformPrice(
            $purchaseMoney,
            $entity->getCurrency(),
            $overallGameStats->getCurrency()
        ));

        if ($entity->getTimePlayed() < 60) {
            $wastedMoney = $this->purchaseService->generateOverallCosts($entity);
            $overallGameStats->addToWastedMoney($this->purchaseService->transformPrice(
                $wastedMoney,
                $entity->getCurrency(),
                $overallGameStats->getCurrency()
            ));
        }

        $overallGameStatsRepository->save($overallGameStats);
    }

    /**
     * @param LifecycleEventArgs $args
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if ($entity instanceof Game === false) {
            return;
        }

        /**
         * @var OverallGameStatsRepository $overallGameStatsRepository
         */
        $overallGameStatsRepository = $args->getEntityManager()->getRepository(OverallGameStats::class);
        $overallGameStats = $this->getOverallGameStats($overallGameStatsRepository);

        $unitOfWork = $args->getEntityManager()->getUnitOfWork();
        $changeSet = $unitOfWork->getEntityChangeSet($entity);

        foreach ($this->statProperties as $key => $statProperty) {
            if (array_key_exists($statProperty, $changeSet)) {
                $diff = $changeSet[$statProperty][1] - $changeSet[$statProperty][0];
                $overallGameStats->$key($diff);
            }
        }

        $overallGameStatsRepository->save($overallGameStats);
    }

    /**
     * @param OverallGameStatsRepository $overallGameStatsRepository
     * @return OverallGameStats
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function getOverallGameStats(OverallGameStatsRepository $overallGameStatsRepository): OverallGameStats
    {
        $overallGameStats = $overallGameStatsRepository->findOneBy(['identifier' => getenv('STEAM_USER_ID')]);

        if (is_null($overallGameStats)) {
            $overallGameStats = new OverallGameStats();
            $overallGameStatsRepository->save($overallGameStats);
        }

        return $overallGameStats;
    }
}
