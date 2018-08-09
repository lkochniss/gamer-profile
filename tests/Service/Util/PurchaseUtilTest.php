<?php

namespace tests\App\Service\Util;

use App\Entity\Game;
use App\Entity\Playtime;
use App\Entity\Purchase;
use App\Entity\User;
use App\Repository\PlaytimeRepository;
use App\Repository\PurchaseRepository;
use App\Service\Util\PurchaseUtil;
use PHPUnit\Framework\TestCase;

/**
 * Class PurchaseUtilTest
 */
class PurchaseUtilTest extends TestCase
{
    public function testGenerateOverallCostsWithoutPurchaseWorks(): void
    {
        $game = new Game();
        $user = new User(1);

        $purchaseRepositoryMock = $this->createMock(PurchaseRepository::class);
        $purchaseRepositoryMock->expects($this->any())
            ->method('findBy')
            ->with(['game' => $game, 'user' => $user])
            ->willReturn([]);

        $playtimeRepositoryMock = $this->createMock(PlaytimeRepository::class);

        $purchaseService = new PurchaseUtil($purchaseRepositoryMock, $playtimeRepositoryMock);

        $this->assertEquals(0, $purchaseService->generateOverallCosts($game, $user));
    }

    public function testGenerateOverallCostsWithOnePurchaseWorks(): void
    {
        $game = new Game();
        $user = new User(1);
        $purchase = new Purchase($user);
        $purchase->setGame($game);
        $purchase->setPrice(1);
        $purchase->setCurrency('USD');

        $purchaseRepositoryMock = $this->createMock(PurchaseRepository::class);
        $purchaseRepositoryMock->expects($this->any())
            ->method('findBy')
            ->with(['game' => $game, 'user' => $user])
            ->willReturn([$purchase]);

        $playtimeRepositoryMock = $this->createMock(PlaytimeRepository::class);

        $purchaseService = new PurchaseUtil($purchaseRepositoryMock, $playtimeRepositoryMock);

        $this->assertEquals(1, $purchaseService->generateOverallCosts($game, $user));
    }

    public function testGenerateOverallCostsWithMultiplePurchasesWorks(): void
    {
        $game = new Game();
        $user = new User(1);
        $purchase = new Purchase($user);
        $purchase->setGame($game);
        $purchase->setPrice(1);
        $purchase->setCurrency('USD');

        $purchaseRepositoryMock = $this->createMock(PurchaseRepository::class);
        $purchaseRepositoryMock->expects($this->any())
            ->method('findBy')
            ->with(['game' => $game, 'user' => $user])
            ->willReturn([$purchase, $purchase]);

        $playtimeRepositoryMock = $this->createMock(PlaytimeRepository::class);

        $purchaseService = new PurchaseUtil($purchaseRepositoryMock, $playtimeRepositoryMock);

        $this->assertEquals(2, $purchaseService->generateOverallCosts($game, $user));
    }

    public function testGenerateCostsPerHourWithoutPurchaseWorks(): void
    {
        $game = new Game();
        $user = new User(1);

        $purchaseRepositoryMock = $this->createMock(PurchaseRepository::class);
        $purchaseRepositoryMock->expects($this->any())
            ->method('findBy')
            ->with(['game' => $game, 'user' => $user])
            ->willReturn([]);

        $playtime = new Playtime($user, $game);

        $playtimeRepositoryMock = $this->createMock(PlaytimeRepository::class);
        $playtimeRepositoryMock->expects($this->any())
            ->method('findOneBy')
            ->with(['game' => $game, 'user' => $user])
            ->willReturn($playtime);

        $purchaseService = new PurchaseUtil($purchaseRepositoryMock, $playtimeRepositoryMock);

        $this->assertEquals(0.0, $purchaseService->generateCostsPerHour($game, $user));
    }

    public function testGenerateCostsPerHourWithNoPlaytimeWorks(): void
    {
        $game = new Game();
        $user = new User(1);
        $purchase = new Purchase($user);
        $purchase->setGame($game);
        $purchase->setPrice(1);
        $purchase->setCurrency('USD');

        $purchaseRepositoryMock = $this->createMock(PurchaseRepository::class);
        $purchaseRepositoryMock->expects($this->any())
            ->method('findBy')
            ->with(['game' => $game, 'user' => $user])
            ->willReturn([$purchase]);

        $playtime = new Playtime($user, $game);

        $playtimeRepositoryMock = $this->createMock(PlaytimeRepository::class);
        $playtimeRepositoryMock->expects($this->any())
            ->method('findOneBy')
            ->with(['game' => $game, 'user' => $user])
            ->willReturn($playtime);

        $purchaseService = new PurchaseUtil($purchaseRepositoryMock, $playtimeRepositoryMock);

        $this->assertEquals(1, $purchaseService->generateCostsPerHour($game, $user));
    }

    public function testGenerateCostsPerHourWithPlaytimeBelowOneHourWorks(): void
    {
        $game = new Game();
        $user = new User(1);
        $purchase = new Purchase($user);
        $purchase->setGame($game);
        $purchase->setPrice(1);
        $purchase->setCurrency('USD');

        $purchaseRepositoryMock = $this->createMock(PurchaseRepository::class);
        $purchaseRepositoryMock->expects($this->any())
            ->method('findBy')
            ->with(['game' => $game, 'user' => $user])
            ->willReturn([$purchase]);

        $playtime = new Playtime($user, $game);
        $playtime->setOverallPlaytime(50);

        $playtimeRepositoryMock = $this->createMock(PlaytimeRepository::class);
        $playtimeRepositoryMock->expects($this->any())
            ->method('findOneBy')
            ->with(['game' => $game, 'user' => $user])
            ->willReturn($playtime);

        $purchaseService = new PurchaseUtil($purchaseRepositoryMock, $playtimeRepositoryMock);

        $this->assertEquals(1, $purchaseService->generateCostsPerHour($game, $user));
    }

    public function testGenerateCostsPerHourWithPlaytimeAboveOneHourWorks(): void
    {
        $game = new Game();
        $user = new User(1);
        $purchase = new Purchase($user);
        $purchase->setGame($game);
        $purchase->setPrice(1);
        $purchase->setCurrency('USD');

        $purchaseRepositoryMock = $this->createMock(PurchaseRepository::class);
        $purchaseRepositoryMock->expects($this->any())
            ->method('findBy')
            ->with(['game' => $game, 'user' => $user])
            ->willReturn([$purchase]);

        $playtime = new Playtime($user, $game);
        $playtime->setOverallPlaytime(120);

        $playtimeRepositoryMock = $this->createMock(PlaytimeRepository::class);
        $playtimeRepositoryMock->expects($this->any())
            ->method('findOneBy')
            ->with(['game' => $game, 'user' => $user])
            ->willReturn($playtime);

        $purchaseService = new PurchaseUtil($purchaseRepositoryMock, $playtimeRepositoryMock);

        $this->assertEquals(0.5, $purchaseService->generateCostsPerHour($game, $user));
    }
}
