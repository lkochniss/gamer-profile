<?php

namespace App\Listener;

use App\Entity\Game;
use App\Entity\OverallGameStats;
use App\Entity\Purchase;
use App\Service\Stats\InvestedMoneyService;
use App\Service\Util\PurchaseUtil;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Class InvestedMoneyListener
 */
class InvestedMoneyListener
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
        return $this->updateInvestedMoney($args);
    }

    /**
     * @param LifecycleEventArgs $args
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postUpdate(LifecycleEventArgs $args): string
    {
        return $this->updateInvestedMoney($args);
    }

    /**
     * @param LifecycleEventArgs $args
     * @return string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function updateInvestedMoney(LifecycleEventArgs $args): string
    {
        $entity = $args->getEntity();

        if (($entity instanceof Game === false) && ($entity instanceof Purchase === false)) {
            return 'S';
        }

        $overallGameStatsRepository = $args->getEntityManager()->getRepository(OverallGameStats::class);
        $purchaseRepository = $args->getEntityManager()->getRepository(Purchase::class);
        $wastedMoneyService = new InvestedMoneyService(
            $this->purchaseUtil,
            $overallGameStatsRepository,
            $purchaseRepository
        );

        $wastedMoneyService->recalculate();

        return 'U';
    }
}
