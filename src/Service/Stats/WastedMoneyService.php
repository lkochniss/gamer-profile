<?php

namespace App\Service\Stats;

use App\Entity\Game;
use App\Entity\OverallGameStats;
use App\Entity\Purchase;
use App\Repository\OverallGameStatsRepository;
use App\Service\Util\PurchaseUtil;

/**
 * Class WastedMoneyService
 */
class WastedMoneyService extends AbstractStatsService
{
    /**
     * @var PurchaseUtil
     */
    private $purchaseUtil;

    /**
     * @var OverallGameStatsRepository
     */
    private $overallGameStatsRepository;

    /**
     * PurchaseService constructor.
     * @param PurchaseUtil $purchaseUtil
     * @param OverallGameStatsRepository $overallGameStatsRepository
     */
    public function __construct(PurchaseUtil $purchaseUtil, OverallGameStatsRepository $overallGameStatsRepository)
    {
        parent::__construct($overallGameStatsRepository);
        $this->purchaseUtil = $purchaseUtil;
        $this->overallGameStatsRepository = $overallGameStatsRepository;
    }

    /**
     * @param Purchase $purchase
     * @return OverallGameStats
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addPurchase(Purchase $purchase): OverallGameStats
    {
        $overallGameStats = $this->getOverallGameStats();

        if ($purchase->getGame()->getTimePlayed() < 60) {
            $overallGameStats->addToWastedMoney($this->purchaseUtil->transformPrice(
                $purchase->getPrice(),
                $purchase->getCurrency(),
                $overallGameStats->getCurrency()
            ));

            $this->overallGameStatsRepository->save($overallGameStats);
        }

        return $overallGameStats;
    }

    /**
     * @param int $diff
     * @param Purchase $purchase
     * @return OverallGameStats
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updatePurchase(int $diff, Purchase $purchase): OverallGameStats
    {
        $overallGameStats = $this->getOverallGameStats();

        if ($purchase->getGame()->getTimePlayed() < 60) {
            $overallGameStats->addToWastedMoney($this->purchaseUtil->transformPrice(
                $diff,
                $purchase->getCurrency(),
                $overallGameStats->getCurrency()
            ));

            $this->overallGameStatsRepository->save($overallGameStats);
        }

        return $overallGameStats;
    }

    /**
     * @param Purchase $purchase
     * @return OverallGameStats
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function removePurchase(Purchase $purchase): OverallGameStats
    {
        $overallGameStats = $this->getOverallGameStats();

        $overallGameStats->addToWastedMoney($this->purchaseUtil->transformPrice(
            $purchase->getPrice() * -1,
            $purchase->getCurrency(),
            $overallGameStats->getCurrency()
        ));

        $this->overallGameStatsRepository->save($overallGameStats);

        return $overallGameStats;
    }

    /**
     * @param Game $game
     * @return OverallGameStats
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addGameDefaultPrice(Game $game): OverallGameStats
    {
        $overallGameStats = $this->getOverallGameStats();

        if ($game->hasGamePurchase()) {
            return $overallGameStats;
        }

        if ($game->getTimePlayed() < 60) {
            $overallGameStats->addToWastedMoney($this->purchaseUtil->transformPrice(
                $game->getPrice(),
                $game->getCurrency(),
                $overallGameStats->getCurrency()
            ));

            $this->overallGameStatsRepository->save($overallGameStats);
        }

        return $overallGameStats;
    }

    /**
     * @param int $diff
     * @param Game $game
     * @return OverallGameStats
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateGameDefaultPrice(int $diff, Game $game): OverallGameStats
    {
        $overallGameStats = $this->getOverallGameStats();

        if ($game->hasGamePurchase()) {
            return $overallGameStats;
        }

        if ($game->getTimePlayed() < 60) {
            $overallGameStats->addToWastedMoney($this->purchaseUtil->transformPrice(
                $diff,
                $game->getCurrency(),
                $overallGameStats->getCurrency()
            ));

            $this->overallGameStatsRepository->save($overallGameStats);
        }

        return $overallGameStats;
    }

    /**
     * @param Purchase $purchase
     * @return OverallGameStats
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function removeGameDefaultPrice(Purchase $purchase): OverallGameStats
    {
        $overallGameStats = $this->getOverallGameStats();

        if ($purchase->getGame()->getTimePlayed() < 60 && $purchase->getType() == Purchase::GAME_PURCHASE) {
            $overallGameStats->addToWastedMoney($this->purchaseUtil->transformPrice(
                $purchase->getGame()->getPrice() * -1,
                $purchase->getCurrency(),
                $overallGameStats->getCurrency()
            ));

            $this->overallGameStatsRepository->save($overallGameStats);
        }

        return $overallGameStats;
    }
}
