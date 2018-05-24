<?php

namespace App\Listener;

use App\Entity\OverallGameStats;
use App\Entity\Purchase;
use App\Service\Stats\WastedMoneyService;
use App\Service\Util\PurchaseUtil;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Class WastedMoneyPurchaseListener
 */
class WastedMoneyPurchaseListener
{
    /**
     * @var PurchaseUtil
     */
    private $purchaseUtil;

    /**
     * PurchaseListener constructor.
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
        /**
         * @var Purchase $entity
         */
        $entity = $args->getEntity();

        if ($entity instanceof Purchase === false) {
            return 'S';
        }

        $overallGameStatsRepository = $args->getEntityManager()->getRepository(OverallGameStats::class);
        $wastedMoneyService = new WastedMoneyService($this->purchaseUtil, $overallGameStatsRepository);

        $wastedMoneyService->addPurchase($entity);
        $wastedMoneyService->removeGameDefaultPrice($entity);

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
         * @var Purchase $entity
         */
        $entity = $args->getEntity();

        if ($entity instanceof Purchase === false) {
            return 'S';
        }

        $unitOfWork = $args->getEntityManager()->getUnitOfWork();
        $changeSet = $unitOfWork->getEntityChangeSet($entity);

        $overallGameStatsRepository = $args->getEntityManager()->getRepository(OverallGameStats::class);
        $wastedMoneyService = new WastedMoneyService($this->purchaseUtil, $overallGameStatsRepository);

        $priceKey = 'price';
        if (array_key_exists($priceKey, $changeSet)) {
            $diff = $changeSet[$priceKey][1] - $changeSet[$priceKey][0];
            $wastedMoneyService->updatePurchase($diff, $entity);
        }

        $timePlayedKey = 'timePlayed';
        if (array_key_exists($timePlayedKey, $changeSet) &&
            $changeSet[$timePlayedKey][0] < 60 &&
            $changeSet[$timePlayedKey][1] >= 60
        ) {
            $wastedMoneyService->removePurchase($entity);
        }

        return 'U';
    }
}
