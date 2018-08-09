<?php

namespace App\Service\Stats;

use App\Entity\OverallGameStats;
use App\Entity\User;
use App\Repository\OverallGameStatsRepository;
use App\Repository\PurchaseRepository;
use App\Service\Util\CurrencyUtil;
use App\Service\Util\PurchaseUtil;

/**
 * Class InvestedMoneyService
 */
class InvestedMoneyService extends AbstractStatsService
{
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
     * @param OverallGameStatsRepository $overallGameStatsRepository
     * @param PurchaseRepository $purchaseRepository
     */
    public function __construct(
        OverallGameStatsRepository $overallGameStatsRepository,
        PurchaseRepository $purchaseRepository
    ) {
        parent::__construct($overallGameStatsRepository);
        $this->overallGameStatsRepository = $overallGameStatsRepository;
        $this->purchaseRepository = $purchaseRepository;
    }

    /**
     * @param User $user
     * @return OverallGameStats
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function recalculate(User $user): OverallGameStats
    {
        $overallGameStats = $this->getOverallGameStats($user);
        $overallGameStats->resetInvestedMoney();

        $purchases = $this->purchaseRepository->findBy(['user' => $user]);
        foreach ($purchases as $purchase) {
            $overallGameStats->addToInvestedMoney(CurrencyUtil::transformPrice(
                $purchase->getPrice(),
                $purchase->getCurrency(),
                $overallGameStats->getCurrency()
            ));
        }
        $this->overallGameStatsRepository->save($overallGameStats);

        return $overallGameStats;
    }
}
