<?php

namespace App\Service\Stats;

use App\Entity\Game;
use App\Entity\OverallGameStats;
use App\Entity\Purchase;
use App\Repository\OverallGameStatsRepository;
use App\Repository\PurchaseRepository;
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
     * @var PurchaseRepository
     */
    private $purchaseRepository;

    /**
     * WastedMoneyService constructor.
     * @param PurchaseUtil $purchaseUtil
     * @param OverallGameStatsRepository $overallGameStatsRepository
     * @param PurchaseRepository $purchaseRepository
     */
    public function __construct(
        PurchaseUtil $purchaseUtil,
        OverallGameStatsRepository $overallGameStatsRepository,
        PurchaseRepository $purchaseRepository
    ) {
        parent::__construct($overallGameStatsRepository);
        $this->purchaseUtil = $purchaseUtil;
        $this->overallGameStatsRepository = $overallGameStatsRepository;
        $this->purchaseRepository = $purchaseRepository;
    }

    /**
     * @return OverallGameStats
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function recalculate(): OverallGameStats
    {
        $overallGameStats = $this->getOverallGameStats();
        $overallGameStats->resetInvestedMoney();

        $purchases = $this->purchaseRepository->findAll();
        foreach ($purchases as $purchase) {
            $overallGameStats->addToInvestedMoney($this->purchaseUtil->transformPrice(
                $purchase->getPrice(),
                $purchase->getCurrency(),
                $overallGameStats->getCurrency()
            ));
        }
        $this->overallGameStatsRepository->save($overallGameStats);

        return $overallGameStats;
    }
}
