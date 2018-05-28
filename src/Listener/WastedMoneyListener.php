<?php

namespace App\Listener;

use App\Entity\Game;
use App\Entity\OverallGameStats;
use App\Entity\Purchase;
use App\Service\Stats\WastedMoneyService;
use App\Service\Util\PurchaseUtil;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Class WastedMoneyListener
 */
class WastedMoneyListener
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
        return $this->updateWastedMoney($args);
    }

    /**
     * @param LifecycleEventArgs $args
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postUpdate(LifecycleEventArgs $args): string
    {
        return $this->updateWastedMoney($args);
    }

    /**
     * @param LifecycleEventArgs $args
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function updateWastedMoney(LifecycleEventArgs $args): string
    {
        $entity = $args->getEntity();

        if (($entity instanceof Game === false) && ($entity instanceof Purchase === false)) {
            return 'S';
        }

        $overallGameStatsRepository = $args->getEntityManager()->getRepository(OverallGameStats::class);
        $purchaseRepository = $args->getEntityManager()->getRepository(Purchase::class);
        $wastedMoneyService = new WastedMoneyService(
            $this->purchaseUtil,
            $overallGameStatsRepository,
            $purchaseRepository
        );

        $wastedMoneyService->recalculate();

        return 'U';
    }
}
