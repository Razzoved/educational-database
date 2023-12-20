<?php

declare(strict_types=1);

namespace App\Libraries;

class Arrays
{
    public static function valueExists($value, $array, $comparator)
    {
        foreach ($array as $a) {
            if ($comparator($a, $value)) return true;
        }
        return false;
    }
}
