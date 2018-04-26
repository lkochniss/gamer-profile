<?php

namespace App\Listener;

use App\Entity\Game;
use App\Entity\OverallGameStats;
use App\Entity\Purchase;
use App\Repository\OverallGameStatsRepository;
use App\Service\PurchaseService;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Class GameListener
 */
class PurchaseListener
{
    /**
     * @var PurchaseService
     */
    private $purchaseService;

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
        /**
         * @var Purchase $entity
         */
        $entity = $args->getEntity();

        if ($entity instanceof Purchase === false) {
            return;
        }

        /**
         * @var OverallGameStatsRepository $overallGameStatsRepository
         */
        $overallGameStatsRepository = $args->getEntityManager()->getRepository(OverallGameStats::class);
        $overallGameStats = $this->getOverallGameStats($overallGameStatsRepository);

        $overallGameStats->addToInvestedMoney($this->purchaseService->transformPrice(
            $entity->getPrice(),
            $entity->getCurrency(),
            $overallGameStats->getCurrency()
        ));

        // Remove game price if it's a game purchase
        if ($entity->getType() == Purchase::GAME_PURCHASE) {
            $overallGameStats->addToInvestedMoney($this->purchaseService->transformPrice(
                $entity->getGame()->getPrice() * -1,
                $entity->getCurrency(),
                $overallGameStats->getCurrency()
            ));
        }

        if ($entity->getGame()->getTimePlayed() < 60) {
            $overallGameStats->addToWastedMoney($this->purchaseService->transformPrice(
                $entity->getPrice(),
                $entity->getCurrency(),
                $overallGameStats->getCurrency()
            ));

            // Remove game price if it's a game purchase
            if ($entity->getType() == Purchase::GAME_PURCHASE) {
                $overallGameStats->addToWastedMoney($this->purchaseService->transformPrice(
                    $entity->getGame()->getPrice() * -1,
                    $entity->getCurrency(),
                    $overallGameStats->getCurrency()
                ));
            }
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
        /**
         * @var Game $entity
         */
        $entity = $args->getEntity();

        if ($entity instanceof Purchase === false) {
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

        $timePlayedKey = 'timePlayed';
        if (array_key_exists($timePlayedKey, $changeSet) &&
            $changeSet[$timePlayedKey][0] < 60 &&
            $changeSet[$timePlayedKey][1] >= 60
        ) {
            $overallGameStats->addToWastedMoney($this->purchaseService->transformPrice(
                $entity->getPrice() * -1,
                $entity->getCurrency(),
                $overallGameStats->getCurrency()
            ));
        }

        $priceKey = 'price';
        if (array_key_exists($priceKey, $changeSet) && $entity->hasGamePurchase() === false) {
            $diff = $changeSet[$priceKey][1] - $changeSet[$priceKey][0];
            $overallGameStats->addToInvestedMoney($this->purchaseService->transformPrice(
                $diff,
                $entity->getCurrency(),
                $overallGameStats->getCurrency()
            ));

            if ($entity->getTimePlayed() < 60) {
                $overallGameStats->addToWastedMoney($this->purchaseService->transformPrice(
                    $diff,
                    $entity->getCurrency(),
                    $overallGameStats->getCurrency()
                ));
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
