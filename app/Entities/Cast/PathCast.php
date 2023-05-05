<?php declare(strict_types = 1);

namespace App\Entities\Cast;

use CodeIgniter\Entity\Cast\BaseCast;

/**
 * Saves all data in form of UNIX paths, since WINDOWS can
 * break string if not careful about it.
 *
 * All resource handlers should implement their own logic
 * of translating paths to their appropriate enviroment.
 *
 * @author Jan Martinek
 */
class PathCast extends BaseCast
{
    public static function get($value, array $params = [])
    {
        return $value ?? "";
    }

    public static function set($value, array $params = [])
    {
        if (substr($value, 0, 4) === "http") {
            return $value;
        }
        return str_replace(
            WINDOWS_SEPARATOR,
            UNIX_SEPARATOR,
            $value
        );
    }
}
