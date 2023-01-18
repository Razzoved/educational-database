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
        if (is_numeric($value)) {
            $value = StatusCast::VALID_VALUES[$value];
        }
        if (!in_array($value, StatusCast::VALID_VALUES)) {
            throw new \Exception('Trying to set invalid status: ' . $value);
        }
        return $value;
    }

    public static function getIndex($value)
    {
        return array_search($value, StatusCast::VALID_VALUES) ?? 0;
    }
}
