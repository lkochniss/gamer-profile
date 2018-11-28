<?php

namespace tests\App\Command\Steam;

use App\Entity\Game;
use App\Entity\GameSession;
use App\Entity\OverallGameStats;
use App\Entity\Purchase;
use App\Listener\InvestedMoneyListener;
use App\Repository\OverallGameStatsRepository;
use App\Repository\PurchaseRepository;
use App\Service\Util\PurchaseUtil;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\UnitOfWork;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class InvestedMoneyListenerTest
 */
class InvestedMoneyListenerTest extends TestCase
{
    /**
     * @var MockObject
     */
    private $overallGameStatsRepositoryMock;

    /**
     * @var MockObject
     */
    private $purchaseRepositoryMock;

    public function setUp()
    {
        $this->overallGameStatsRepositoryMock = $this->createMock(OverallGameStatsRepository::class);
        $this->purchaseRepositoryMock = $this->createMock(PurchaseRepository::class);

        $this->purchaseRepositoryMock->expects($this->any())
            ->method('findAll')
            ->willReturn([]);
    }

    public function testPostPersistSkipsOnWrongEntity(): void
    {
        $argsMock = $this->createMock(LifecycleEventArgs::class);
        $argsMock->expects($this->any())
            ->method('getEntity')
            ->willReturn(new GameSession(new Game()));

        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->expects($this->any())
            ->method('getRepository')
            ->with($this->logicalOr(
                OverallGameStats::class,
                Purchase::class
            ))
            ->will($this->returnCallback([$this, 'getEntityManagerCallback']));

        $argsMock->expects($this->any())
            ->method('getEntityManager')
            ->willReturn($entityManagerMock);

        $purchaseUtilMock = $this->createMock(PurchaseUtil::class);
        $investedMoneyPurchaseListener = new InvestedMoneyListener($purchaseUtilMock);

        $this->assertEquals('S', $investedMoneyPurchaseListener->postPersist($argsMock));
    }

    public function testPostPersistWorksCorrectOnPurchaseEntity(): void
    {
        $purchase = new Purchase();
        $purchase->setPrice(10);
        $purchase->setType(Purchase::OTHER_PURCHASE);
        $purchase->setCurrency(getenv('DEFAULT_CURRENCY'));

        $argsMock = $this->createMock(LifecycleEventArgs::class);
        $argsMock->expects($this->any())
            ->method('getEntity')
            ->willReturn($purchase);

        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->expects($this->any())
            ->method('getRepository')
            ->with($this->logicalOr(
                OverallGameStats::class,
                Purchase::class
            ))
            ->will($this->returnCallback([$this, 'getEntityManagerCallback']));

        $argsMock->expects($this->any())
            ->method('getEntityManager')
            ->willReturn($entityManagerMock);

        $purchaseUtilMock = $this->createMock(PurchaseUtil::class);

        $investedMoneyPurchaseListener = new InvestedMoneyListener($purchaseUtilMock);

        $this->assertEquals('U', $investedMoneyPurchaseListener->postPersist($argsMock));
    }

    public function testPostPersistWorksCorrectOnGameEntity(): void
    {
        $purchase = new Purchase();
        $purchase->setPrice(10);
        $purchase->setType(Purchase::OTHER_PURCHASE);
        $purchase->setCurrency(getenv('DEFAULT_CURRENCY'));

        $game = new Game();
        $game->addPurchase($purchase);

        $argsMock = $this->createMock(LifecycleEventArgs::class);
        $argsMock->expects($this->any())
            ->method('getEntity')
            ->willReturn($game);

        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->expects($this->any())
            ->method('getRepository')
            ->with($this->logicalOr(
                OverallGameStats::class,
                Purchase::class
            ))
            ->will($this->returnCallback([$this, 'getEntityManagerCallback']));

        $argsMock->expects($this->any())
            ->method('getEntityManager')
            ->willReturn($entityManagerMock);

        $purchaseUtilMock = $this->createMock(PurchaseUtil::class);

        $investedMoneyPurchaseListener = new InvestedMoneyListener($purchaseUtilMock);

        $this->assertEquals('U', $investedMoneyPurchaseListener->postPersist($argsMock));
    }

    public function testPostUpdateSkipsOnWrongEntity(): void
    {
        $argsMock = $this->createMock(LifecycleEventArgs::class);
        $argsMock->expects($this->any())
            ->method('getEntity')
            ->willReturn(new GameSession(new Game()));

        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->expects($this->any())
            ->method('getRepository')
            ->with($this->logicalOr(
                OverallGameStats::class,
                Purchase::class
            ))
            ->will($this->returnCallback([$this, 'getEntityManagerCallback']));

        $argsMock->expects($this->any())
            ->method('getEntityManager')
            ->willReturn($entityManagerMock);

        $purchaseUtilMock = $this->createMock(PurchaseUtil::class);
        $investedMoneyPurchaseListener = new InvestedMoneyListener($purchaseUtilMock);

        $this->assertEquals('S', $investedMoneyPurchaseListener->postUpdate($argsMock));
    }

    /**
     * @param array $changeSet
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @dataProvider changeSetProvider
     */
    public function testPostUpdateWorksCorrectWithGamePurchase(array $changeSet): void
    {
        $purchase = new Purchase();
        $purchase->setPrice(10);
        $purchase->setCurrency(getenv('DEFAULT_CURRENCY'));

        $argsMock = $this->createMock(LifecycleEventArgs::class);
        $argsMock->expects($this->any())
            ->method('getEntity')
            ->willReturn($purchase);

        $entityManagerMock = $this->createMock(EntityManager::class);
        $entityManagerMock->expects($this->any())
            ->method('getRepository')
            ->with($this->logicalOr(
                OverallGameStats::class,
                Purchase::class
            ))
            ->will($this->returnCallback([$this, 'getEntityManagerCallback']));

        $unitOfWorkMock = $this->createMock(UnitOfWork::class);
        $unitOfWorkMock->expects($this->any())
            ->method('getEntityChangeSet')
            ->willReturn($changeSet);

        $entityManagerMock->expects($this->any())
            ->method('getUnitOfWork')
            ->willReturn($unitOfWorkMock);

        $argsMock->expects($this->any())
            ->method('getEntityManager')
            ->willReturn($entityManagerMock);

        $purchaseUtilMock = $this->createMock(PurchaseUtil::class);
        $investedMoneyPurchaseListener = new InvestedMoneyListener($purchaseUtilMock);

        $this->assertEquals('U', $investedMoneyPurchaseListener->postUpdate($argsMock));
    }


    /**
     * @return array
     */
    public function changeSetProvider(): array
    {
        return [
            [
                []
            ],
            [
                [
                    'price' => [0, 1]
                ]
            ],
        ];
    }

    /**
     * @param string $entityManagerClass
     * @return MockObject
     */
    public function getEntityManagerCallback(string $entityManagerClass): MockObject
    {
        if ($entityManagerClass === OverallGameStats::class) {
            return $this->overallGameStatsRepositoryMock;
        }

        return $this->purchaseRepositoryMock;
    }
}
