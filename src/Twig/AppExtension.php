<?php

namespace App\Twig;

use App\Entity\Game;
use App\Service\Util\PurchaseUtil;
use App\Service\Transformation\TimeTransformation;
use Symfony\Component\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class AppExtension
 */
class AppExtension extends AbstractExtension
{
    private $translator;

    /**
     * AppExtension constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('convert_overall_time', [$this, 'convertOverallTime']),
            new TwigFilter('convert_recent_time', [$this, 'convertRecentTime']),
            new TwigFilter('get_game_overall_costs', [$this, 'getGameOverallCosts']),
            new TwigFilter('get_game_costs_per_hour', [$this, 'getGameCostsPerHour'])
        ];
    }

    /**
     * @param int $value
     * @return string
     */
    public function convertRecentTime(int $value): string
    {
        $time = new TimeTransformation($value);

        $hours = $this->translator->transChoice(
            '{0}%count% hours|{1}1 hour|]1,Inf[%count% hours',
            $time->getTimeInHours(),
            [],
            'messages'
        );

        $minutes = $this->translator->transChoice(
            '{0}%count% minutes|{1}1 minute|]1,Inf[%count% minutes',
            $time->getMinutes(),
            [],
            'messages'
        );

        return sprintf(
            '%s and %s',
            $hours,
            $minutes
        );
    }

    /**
     * @param int $value
     * @return string
     */
    public function convertOverallTime(int $value): string
    {
        $time = new TimeTransformation($value);

        $days = $this->translator->transChoice(
            '{0}%count% days|{1}1 day|]1,Inf[%count% days',
            $time->getDays(),
            [],
            'messages'
        );

        $hours = $this->translator->transChoice(
            '{0}%count% hours|{1}1 hour|]1,Inf[%count% hours',
            $time->getHours(),
            [],
            'messages'
        );

        $minutes = $this->translator->transChoice(
            '{0}%count% minutes|{1}1 minute|]1,Inf[%count% minutes',
            $time->getMinutes(),
            [],
            'messages'
        );

        return sprintf(
            '%s, %s and %s',
            $days,
            $hours,
            $minutes
        );
    }

    /**
     * @param Game $game
     * @return float
     */
    public function getGameOverallCosts(Game $game): float
    {
        $purchaseService  = new PurchaseUtil();

        return $purchaseService->generateOverallCosts($game);
    }

    /**
     * @param Game $game
     * @return float
     */
    public function getGameCostsPerHour(Game $game): float
    {
        $purchaseService  = new PurchaseUtil();

        return $purchaseService->generateCostsPerHour($game);
    }
}
