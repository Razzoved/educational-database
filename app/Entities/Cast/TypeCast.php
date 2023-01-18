<?php declare(strict_types = 1);

namespace App\Entities\Cast;

use CodeIgniter\Entity\Cast\BaseCast;

class TypeCast extends BaseCast
{
    const VALID_VALUES = [
        'Document',
        'Video',
    ];

    public static function get($value, array $params = [])
    {
        return $value;
    }

    public static function set($value, array $params = [])
    {
        if (is_numeric($value)) {
            $value = TypeCast::VALID_VALUES[$value];
        }
        if (!in_array($value, TypeCast::VALID_VALUES)) {
            throw new \Exception('Trying to set invalid type: ' . $value);
        }
        return $value;
    }

    public static function getIndex($value)
    {
        return array_search($value, TypeCast::VALID_VALUES) ?? 0;
    }
}
