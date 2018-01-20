<?php

namespace App\Service;

/**
 * Class SlugifyService
 */
class SlugifyService
{
    public function slugify(string $string)
    {
        $string = strtolower($string);
        $string = str_replace('ä', 'ae', $string);
        $string = str_replace('ö', 'oe', $string);
        $string = str_replace('ü', 'ue', $string);
        $string = str_replace('ß', 'ss', $string);
        $string = str_replace('&', 'and', $string);
        $string = preg_replace('/[^a-z0-9]+/', '-', $string);

        if (substr($string, -1) ==='-') {
            $string = substr($string, 0, -1);
        }
        return $string;
    }
}
