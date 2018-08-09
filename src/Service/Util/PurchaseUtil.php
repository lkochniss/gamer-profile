<?php

namespace App\Service\Util;

use App\Entity\Game;
use App\Entity\Purchase;
use App\Entity\User;
use App\Repository\PlaytimeRepository;
use App\Repository\PurchaseRepository;

/**
 * Class PurchaseUtil
 */
class PurchaseUtil
{
    /**
     * @var PurchaseRepository
     */
    private $purchaseRepository;

    /**
     * @var PlaytimeRepository
     */
    private $playtimeRepository;

    /**
     * PurchaseUtil constructor.
     * @param PurchaseRepository $purchaseRepository
     * @param PlaytimeRepository $playtimeRepository
     */
    public function __construct(PurchaseRepository $purchaseRepository, PlaytimeRepository $playtimeRepository)
    {
        $this->purchaseRepository = $purchaseRepository;
        $this->playtimeRepository = $playtimeRepository;
    }

    /**
     * @param Game $game
     * @param User $user
     * @return float
     */
    public function generateOverallCosts(Game $game, User $user): float
    {
        $sum = 0;

        $purchases = $this->purchaseRepository->findBy(['game' => $game, 'user' => $user]);

        foreach ($purchases as $purchase) {
            $sum += CurrencyUtil::transformPrice($purchase->getPrice(), $purchase->getCurrency(), $game->getCurrency());
        }

        return $sum;
    }

    /**
     * @param Game $game
     * @param User $user
     * @return float
     */
    public function generateCostsPerHour(Game $game, User $user): float
    {
        $playtime = $this->playtimeRepository->findOneBy(['game' => $game, 'user' => $user]);

        if (is_null($playtime)) {
            return 0.0;
        }

        $time = $playtime->getOverallPlaytime() > 60 ? $playtime->getOverallPlaytime() : 60;

        return round($this->generateOverallCosts($game, $user) / ($time / 60), 2);
    }
}
