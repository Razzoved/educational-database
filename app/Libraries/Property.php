<?php declare(strict_types = 1);

namespace App\Libraries;

class Property
{
    public static function getFilters(array $source, array $ignore = ['search', 'sort', 'sortDir', 'page']) : array
    {
        $filters = [];
        foreach ($source as $key => $value) {
            if (!is_int($key)) {
                $key = str_replace('#', '', $key);
                $key = str_replace('_', ' ', $key);
            }
            if (!in_array($key, $ignore, true)) {
                $filters[$key] = $value;
            }
        }
        return $filters;
    }
}
