<?php declare(strict_types = 1);

namespace App\Entities\Cast;

use CodeIgniter\Entity\Cast\BaseCast;

class StatusCast extends BaseCast
{
    public const PUBLIC = 'Published';

    public const VALID_VALUES = [
        'Draft',
        'Pending review',
        StatusCast::PUBLIC,
    ];

    public static function get($value, array $params = [])
    {
        return $value;
    }

    public static function set($value, array $params = [])
    {
        if (self::isValid($value)) {
            return $value;
        } else if (self::isValidIndex($value)) {
            return self::VALID_VALUES[$value];
        }
        return self::VALID_VALUES[0];
    }

    public static function getIndex($value) : int
    {
        return array_search($value, StatusCast::VALID_VALUES) ?? 0;
    }

    public static function isValid($value) : bool
    {
        return in_array($value, StatusCast::VALID_VALUES);
    }

    public static function isValidIndex($index) : bool
    {
        if (!is_numeric($index)) {
            return false;
        }
        return $index >= 0 && $index < sizeof(self::VALID_VALUES);
    }
}
