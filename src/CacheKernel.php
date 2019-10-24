<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\HttpCache\HttpCache;

/**
 * Class CacheKernel
 */
class CacheKernel extends HttpCache
{
    protected function getOptions(): array
    {
        return [
            'default_ttl' => 60,
        ];
    }
}
