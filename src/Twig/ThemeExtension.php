<?php

namespace App\Twig;

use App\Service\Util\ThemeService;
use Symfony\Component\HttpFoundation\Request;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class ThemeExtension
 */
class ThemeExtension extends AbstractExtension
{
    /**
     * @var ThemeService
     */
    private $themeService;

    /**
     * ThemeExtension constructor.
     * @param ThemeService $themeService
     */
    public function __construct(ThemeService $themeService)
    {
        $this->themeService = $themeService;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('is_dark_theme', [$this, 'isDarkTheme']),
        ];
    }

    /**
     * @param int $value
     * @return string
     */
    public function convertRecentTime(int $value): string
    {
        return $this->timeConverterService->convertRecentTime($value);
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function isDarkTheme(Request $request): bool
    {
        return $this->themeService->isDarkTheme($request);
    }
}
