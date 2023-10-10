<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('array_intersect', [$this, 'arrayIntersect']),
        ];
    }

    public function arrayIntersect($array1, $array2)
    {
        return array_intersect($array1, $array2);
    }
}
