<?php

namespace App\Listener;

use App\Entity\Game;
use App\Entity\OverallGameStats;
use App\Service\Stats\InvestedMoneyService;
use App\Service\Util\PurchaseUtil;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Class InvestedMoneyListener
 */
class InvestedMoneyGameListener
{
    /**
     * @var PurchaseUtil
     */
    private $purchaseUtil;

    /**
     * InvestedMoneyListener constructor.
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
        $investedMoneyService = new InvestedMoneyService($this->purchaseUtil, $overallGameStatsRepository);

        $investedMoneyService->addGameDefaultPrice($entity);

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
        $investedMoneyService = new InvestedMoneyService($this->purchaseUtil, $overallGameStatsRepository);

        $unitOfWork = $args->getEntityManager()->getUnitOfWork();
        $changeSet = $unitOfWork->getEntityChangeSet($entity);

        $priceKey = 'price';
        if (array_key_exists($priceKey, $changeSet)) {
            $diff = $changeSet[$priceKey][1] - $changeSet[$priceKey][0];
            $investedMoneyService->updateGameDefaultPrice($diff, $entity);
        }

        return 'U';
    }
}
