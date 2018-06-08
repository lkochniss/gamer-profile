<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\GameSession;
use App\Repository\GameRepository;
use App\Repository\GameSessionRepository;
use App\Repository\PlaytimePerMonthRepository;
use App\Repository\PurchaseRepository;
use App\Service\Util\TimeConverterUtil;
use App\Service\Util\PurchaseUtil;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ApiController
 */
class ApiController extends Controller
{
    /**
     * @var TimeConverterUtil
     */
    private $timeConverterService;

    /**
     * ApiController constructor.
     * @param TimeConverterUtil $timeConverterService
     */
    public function __construct(TimeConverterUtil $timeConverterService)
    {
        $this->timeConverterService = $timeConverterService;
    }

    /**
     * @param PlaytimePerMonthRepository $playtimePerMonthRepository
     * @return JsonResponse
     */
    public function sessionsPerMonth(PlaytimePerMonthRepository $playtimePerMonthRepository): JsonResponse
    {
        $playtimePerMonth = $playtimePerMonthRepository->findAll();

        $data = [];
        foreach ($playtimePerMonth as $playtime) {
            $data[] = [
                'date' => $playtime->getMonth()->format('M Y'),
                'timeInMinutes' => $playtime->getDuration(),
                'timeForTooltip' => $this->timeConverterService->convertOverallTime($playtime->getDuration())
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * @param GameSessionRepository $gameSessionRepository
     * @return JsonResponse
     */
    public function sessionsLastDays(GameSessionRepository $gameSessionRepository): JsonResponse
    {
        $sessions = $gameSessionRepository->findForLastDays();

        return new JsonResponse($this->mapSessionData($sessions));
    }

    /**
     * @param int $id
     * @param GameRepository $gameRepository
     * @return JsonResponse
     */
    public function sessionsForGame(int $id, GameRepository $gameRepository): JsonResponse
    {
        /**
         * @var Game $game
         */
        $game = $gameRepository->find($id);

        if (!$game) {
            throw new NotFoundHttpException();
        }

        $data = [];

        /**
         * @var GameSession $session
         */
        foreach ($game->getGameSessions() as $session) {
            $data[] = [
                'date' => $session->getCreatedAt()->format('d M Y'),
                'timeInMinutes' => $session->getDuration(),
                'timeForTooltip' => $this->timeConverterService->convertRecentTime($session->getDuration())
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * @param PurchaseRepository $purchaseRepository
     * @param PurchaseUtil $purchaseUtil
     * @return JsonResponse
     */
    public function investedMoneyPerMonth(
        PurchaseRepository $purchaseRepository,
        PurchaseUtil $purchaseUtil
    ): JsonResponse {
        $purchases = $purchaseRepository->findForLastTwelveMonth();

        return new JsonResponse($this->mapInvestedMoneyData($purchases, 'M Y', $purchaseUtil));
    }

    /**
     * @param PurchaseRepository $purchaseRepository
     * @param PurchaseUtil $purchaseUtil
     * @return JsonResponse
     */
    public function investedMoneyPerYear(
        PurchaseRepository $purchaseRepository,
        PurchaseUtil $purchaseUtil
    ): JsonResponse {
        $purchases = $purchaseRepository->findAll();

        return new JsonResponse($this->mapInvestedMoneyData($purchases, 'Y', $purchaseUtil));
    }

    /**
     * @param GameSessionRepository $sessionRepository
     * @return JsonResponse
     */
    public function sessionsThisYear(GameSessionRepository $sessionRepository): JsonResponse
    {
        $sessions = $sessionRepository->findForThisYear();

        return new JsonResponse($this->mapSessionData($sessions));
    }

    /**
     * @param array $purchases
     * @param string $format
     * @param PurchaseUtil $purchaseUtil
     * @return array
     */
    private function mapInvestedMoneyData(array $purchases, string $format, PurchaseUtil $purchaseUtil): array
    {
        $defaultCurrency = getenv('DEFAULT_CURRENCY');

        $data = [];
        foreach ($purchases as $purchase) {
            $key = $purchase->getBoughtAt()->format($format);
            if (!array_key_exists($key, $data)) {
                $data[$key] = 0;
            }
            $data[$key] += $purchaseUtil->transformPrice(
                $purchase->getPrice(),
                $purchase->getCurrency(),
                $defaultCurrency
            );
        }

        $data = array_map(function ($date, $money) {
            return [
                'price' => round($money, 2),
                'currency' => getenv('DEFAULT_CURRENCY'),
                'date' => $date,
            ];
        }, array_keys($data), $data);

        usort($data, function ($a, $b) {
            return strtotime($a["date"]) - strtotime($b["date"]);
        });

        return $data;
    }

    /**
     * @param array $sessions
     * @return array
     */
    private function mapSessionData(array $sessions): array
    {
        $data = [];

        /**
         * @var GameSession $session
         */
        foreach ($sessions as $session) {
            $key = $session->getCreatedAt()->format('d M Y');
            if (!array_key_exists($key, $data)) {
                $data[$key] = 0;
            }
            $data[$key] += $session->getDuration();
        }

        $data = array_map(function ($month, $duration) {
            return [
                'date' => $month,
                'timeInMinutes' => $duration,
                'timeForTooltip' => $this->timeConverterService->convertRecentTime($duration)
            ];
        }, array_keys($data), $data);

        return $data;
    }
}
