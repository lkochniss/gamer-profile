<?php

namespace App\Listener;

use App\Entity\Game;
use App\Entity\OverallGameStats;
use App\Entity\Purchase;
use App\Service\Stats\InvestedMoneyService;
use App\Service\Util\PurchaseUtil;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Class PurchaseListener
 */
class InvestedMoneyPurchaseListener
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
        $investedMoneyService = new InvestedMoneyService($this->purchaseUtil, $overallGameStatsRepository);

        $investedMoneyService->addPurchase($entity);
        $investedMoneyService->removeGameDefaultPrice($entity);

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

        if ($entity instanceof Purchase === false) {
            return 'S';
        }

        $unitOfWork = $args->getEntityManager()->getUnitOfWork();
        $changeSet = $unitOfWork->getEntityChangeSet($entity);

        $overallGameStatsRepository = $args->getEntityManager()->getRepository(OverallGameStats::class);
        $investedMoneyService = new InvestedMoneyService($this->purchaseUtil, $overallGameStatsRepository);

        $priceKey = 'price';
        if (array_key_exists($priceKey, $changeSet)) {
            $diff = $changeSet[$priceKey][1] - $changeSet[$priceKey][0];
            $investedMoneyService->updatePurchase($diff, $entity);
        }

        return 'U';
    }
}
