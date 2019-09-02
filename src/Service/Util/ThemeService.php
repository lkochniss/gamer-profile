<?php

namespace App\Service\Util;

use Symfony\Component\HttpFoundation\Request;

class ThemeService
{
    /**
     * @param Request $request
     * @return bool
     */
    public function isDarkTheme(Request $request): bool
    {
        if ($request->cookies->has('darkTheme') && $request->cookies->get('darkTheme') === 'true') {
            return true;
        }

        return false;
    }
}
