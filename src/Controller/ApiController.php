<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\GameSession;
use App\Repository\GameRepository;
use App\Repository\GameSessionRepository;
use App\Repository\PlaytimePerMonthRepository;
use App\Repository\PurchaseRepository;
use App\Service\Util\CurrencyUtil;
use App\Service\Util\TimeConverterUtil;
use App\Service\Util\PurchaseUtil;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\User\UserInterface;

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
     * @param UserInterface $user
     * @return JsonResponse
     */
    public function sessionsPerMonth(
        PlaytimePerMonthRepository $playtimePerMonthRepository,
        UserInterface $user
    ): JsonResponse {
        $playtimePerMonth = $playtimePerMonthRepository->findBy(['user' => $user]);

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
     * @param PlaytimePerMonthRepository $playtimePerMonthRepository
     * @param UserInterface $user
     * @return JsonResponse
     */
    public function averagePerMonth(
        PlaytimePerMonthRepository $playtimePerMonthRepository,
        UserInterface $user
    ): JsonResponse {
        $playtimePerMonth = $playtimePerMonthRepository->findBy(['user' => $user]);
        $today = new \DateTime();

        $data = [];
        foreach ($playtimePerMonth as $playtime) {
            $lastDayOfMonth = new \DateTime(' last day of ' . $playtime->getMonth()->format('M Y'));

            if ($today->format('M-Y') == $lastDayOfMonth->format('M-Y')) {
                $lastDayOfMonth = $today;
            }

            $average = $playtime->getDuration() / $lastDayOfMonth->format('d');
            $data[] = [
                'date' => $playtime->getMonth()->format('M Y'),
                'timeInMinutes' => $average,
                'timeForTooltip' => $this->timeConverterService->convertRecentTime($average)
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * @param GameSessionRepository $gameSessionRepository
     * @param UserInterface $user
     * @return JsonResponse
     */
    public function sessionsLastDays(GameSessionRepository $gameSessionRepository, UserInterface $user): JsonResponse
    {
        $sessions = $gameSessionRepository->findForLastDays($user);

        return new JsonResponse($this->mapSessionData($sessions));
    }

    /**
     * @param int $id
     * @param GameRepository $gameRepository
     * @param GameSessionRepository $sessionRepository
     * @param UserInterface $user
     * @return JsonResponse
     */
    public function sessionsForGame(
        int $id,
        GameRepository $gameRepository,
        GameSessionRepository $sessionRepository,
        UserInterface $user
    ): JsonResponse {
        /**
         * @var Game $game
         */
        $game = $gameRepository->find($id);

        if (!$game) {
            throw new NotFoundHttpException();
        }
        $sessions = $sessionRepository->findBy(['game' => $game, 'user' => $user]);

        $data = [];

        /**
         * @var GameSession $session
         */
        foreach ($sessions as $session) {
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
     * @param UserInterface $user
     * @return JsonResponse
     */
    public function investedMoneyPerMonth(PurchaseRepository $purchaseRepository, UserInterface $user
    ): JsonResponse {
        $purchases = $purchaseRepository->findForLastTwelveMonth($user);

        return new JsonResponse($this->mapInvestedMoneyData($purchases, 'M Y'));
    }

    /**
     * @param PurchaseRepository $purchaseRepository
     * @param PurchaseUtil $purchaseUtil
     * @param UserInterface $user
     * @return JsonResponse
     */
    public function investedMoneyPerYear(
        PurchaseRepository $purchaseRepository,
        PurchaseUtil $purchaseUtil,
        UserInterface $user
    ): JsonResponse {
        $purchases = $purchaseRepository->findBy(['user' => $user]);

        return new JsonResponse($this->mapInvestedMoneyData($purchases, 'Y', $purchaseUtil));
    }

    /**
     * @param GameSessionRepository $sessionRepository
     * @param UserInterface $user
     * @return JsonResponse
     */
    public function sessionsThisYear(GameSessionRepository $sessionRepository, UserInterface $user): JsonResponse
    {
        $sessions = $sessionRepository->findForThisYear($user);

        return new JsonResponse($this->mapSessionData($sessions));
    }

    /**
     * @param array $purchases
     * @param string $format
     * @return array
     */
    private function mapInvestedMoneyData(array $purchases, string $format): array
    {
        $defaultCurrency = getenv('DEFAULT_CURRENCY');

        $data = [];
        foreach ($purchases as $purchase) {
            $key = $purchase->getBoughtAt()->format($format);
            if (!array_key_exists($key, $data)) {
                $data[$key] = 0;
            }
            $data[$key] += CurrencyUtil::transformPrice(
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
