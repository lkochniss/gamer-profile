<?php

namespace App\Listener;

use App\Entity\Game;
use App\Entity\OverallGameStats;
use App\Service\Stats\WastedMoneyService;
use App\Service\Util\PurchaseUtil;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Class WastedMoneyListener
 */
class WastedMoneyGameListener
{
    /**
     * @var PurchaseUtil
     */
    private $purchaseUtil;

    /**
     * GameListener constructor.
     * @param PurchaseUtil $purchaseUtil
     */
    public function __construct(PurchaseUtil $purchaseUtil)
    {
        $this->purchaseUtil = $purchaseUtil;
    }

    /**
     * @param LifecycleEventArgs $args
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postPersist(LifecycleEventArgs $args): string
    {
        $entity = $args->getEntity();

        if ($entity instanceof Game === false) {
            return 'S';
        }

        $overallGameStatsRepository = $args->getEntityManager()->getRepository(OverallGameStats::class);
        $wastedMoneyService = new WastedMoneyService($this->purchaseUtil, $overallGameStatsRepository);

        $wastedMoneyService->addGameDefaultPrice($entity);

        return 'U';
    }

    /**
     * @param LifecycleEventArgs $args
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postUpdate(LifecycleEventArgs $args): string
    {
        /**
         * @var Game $entity
         */
        $entity = $args->getEntity();

        if ($entity instanceof Game === false) {
            return 'S';
        }

        $overallGameStatsRepository = $args->getEntityManager()->getRepository(OverallGameStats::class);
        $wastedMoneyService = new WastedMoneyService($this->purchaseUtil, $overallGameStatsRepository);

        $unitOfWork = $args->getEntityManager()->getUnitOfWork();
        $changeSet = $unitOfWork->getEntityChangeSet($entity);

        $priceKey = 'price';
        if (array_key_exists($priceKey, $changeSet)) {
            $diff = $changeSet[$priceKey][1] - $changeSet[$priceKey][0];
            $wastedMoneyService->updateGameDefaultPrice($diff, $entity);
        }

        $timePlayedKey = 'timePlayed';
        if (array_key_exists($timePlayedKey, $changeSet) &&
            $changeSet[$timePlayedKey][0] < 60 &&
            $changeSet[$timePlayedKey][1] >= 60
        ) {
            $wastedMoneyService->updateGameDefaultPrice(
                $this->purchaseUtil->generateOverallCosts($entity) * -1,
                $entity
            );
        }

        return 'U';
    }
}
