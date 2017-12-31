<?php

namespace App\Service\Twig;

use App\Repository\GameRepository;
use App\Service\Transformator\TimeTransformator;
use App\Service\TranslationService;

/**
 * Class HomepageTransformatorService
 */
class HomepageTransformatorService
{
    /**
     * @var TranslationService
     */
    private $translator;

    /**
     * @var GameRepository
     */
    private $gameRepository;

    /**
     * HomepageTransformatorService constructor.
     * @param GameRepository $gameRepository
     * @param TranslationService $translationService
     */
    public function __construct(GameRepository $gameRepository, TranslationService $translationService)
    {
        $this->gameRepository = $gameRepository;
        $this->translator = $translationService;
    }

    /**
     * @return array
     */
    public function transformTopPlayedGames(): array
    {
        $transformedPlayedGames = [];
        foreach ($this->gameRepository->getMostPlayedGames(5) as $game) {
            $transformedPlayedGames[] = [
                'image' => $game->getHeaderImagePath(),
                'playtime' => $this->translator->trans(
                    'game.%name% overall played for %time%',
                    [
                        '%name%' => $game->getName(),
                        '%time%' => $this->translateTime(new TimeTransformator($game->getTimePlayed()))
                    ]
                )
            ];
        }

        return $transformedPlayedGames;
    }

    /**
     * @return array
     */
    public function transformRecentlyPlayedGames(): array
    {
        $transformedPlayedGames = [];
        foreach ($this->gameRepository->getRecentlyPlayedGames() as $game) {
            $transformedPlayedGames[] = [
                'image' => $game->getHeaderImagePath(),
                'playtime' => $this->translator->trans(
                    'game.%name% played for %time% recently',
                    [
                        '%name%' => $game->getName(),
                        '%time%' => $this->translateTime(new TimeTransformator($game->getRecentlyPlayed()))
                    ]
                )
            ];
        }

        return $transformedPlayedGames;
    }

    private function translateTime(TimeTransformator $time)
    {
        $minutes = $this->translator->transChoice(
            '{0}|{1} %count% %minute% |]1,Inf[ %count% %minutes%',
            $time->getMinutes(),
            [
                '%minute%' => $this->translator->trans('time.minute'),
                '%minutes%' => $this->translator->trans('time.minutes')
            ]
        );

        $hours = $this->translator->transChoice(
            '{0}|{1} %count% %hour% |]1,Inf[ %count% %hours%',
            $time->getHours(),
            [
                '%hour%' => $this->translator->trans('time.hour'),
                '%hours%' => $this->translator->trans('time.hours')
            ]
        );

        $days = $this->translator->transChoice(
            '{0}|{1} %count% %day% |]1,Inf[ %count% %days%',
            $time->getDays(),
            [
                '%day%' => $this->translator->trans('time.day'),
                '%days%' => $this->translator->trans('time.days')
            ]
        );

        return $this->decideOutput($days, $hours, $minutes);
    }

    /**
     * @param string $days
     * @param string $hours
     * @param string $minutes
     * @return string
     */
    private function decideOutput(string $days, string $hours, string $minutes): string
    {
        if (empty($days)) {
            if (empty($hours)) {
                if (empty($minutes)) {
                    $output = $this->translator->trans('game.not_played');
                } else {
                    $output = $minutes;
                }
            } else {
                $output = sprintf(
                    '%s and %s',
                    $hours,
                    $minutes
                );
            }
        } else {
            if (empty($hours)) {
                if (empty($minutes)) {
                    $output = $days;
                } else {
                    $output = sprintf(
                        '%s and %s',
                        $days,
                        $minutes
                    );
                }
            } else {
                $output = sprintf(
                    '%s, %s and %s',
                    $days,
                    $hours,
                    $minutes
                );
            }
        }

        return $output;
    }
}
