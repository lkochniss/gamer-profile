<?php

namespace App\Service\Stats;

use App\Entity\Game;
use App\Entity\OverallGameStats;
use App\Entity\Purchase;
use App\Repository\OverallGameStatsRepository;
use App\Service\Util\PurchaseUtil;

/**
 * Class InvestedMoneyService
 */
class InvestedMoneyService extends AbstractStatsService
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

        $overallGameStats->addToInvestedMoney($this->purchaseUtil->transformPrice(
            $purchase->getPrice(),
            $purchase->getCurrency(),
            $overallGameStats->getCurrency()
        ));

        $this->overallGameStatsRepository->save($overallGameStats);

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

        $overallGameStats->addToInvestedMoney($this->purchaseUtil->transformPrice(
            $diff,
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
            $overallGameStats;
        }

        $overallGameStats->addToInvestedMoney($this->purchaseUtil->transformPrice(
            $game->getPrice(),
            $game->getCurrency(),
            $overallGameStats->getCurrency()
        ));

        $this->overallGameStatsRepository->save($overallGameStats);

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

        $overallGameStats->addToInvestedMoney($this->purchaseUtil->transformPrice(
            $diff,
            $game->getCurrency(),
            $overallGameStats->getCurrency()
        ));

        $this->overallGameStatsRepository->save($overallGameStats);

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

        if ($purchase->getType() == Purchase::GAME_PURCHASE) {
            $overallGameStats->addToInvestedMoney($this->purchaseUtil->transformPrice(
                $purchase->getGame()->getPrice() * -1,
                $purchase->getCurrency(),
                $overallGameStats->getCurrency()
            ));
        }

        $this->overallGameStatsRepository->save($overallGameStats);

        return $overallGameStats;
    }
}
