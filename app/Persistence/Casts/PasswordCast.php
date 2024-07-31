<?php declare(strict_types=1);

namespace App\Persistence\Casts;

use CodeIgniter\Entity\Cast\BaseCast;

/**
 * Automatically hashes password when setting the value.
 * There is no need to do it manually now.
 *
 * @author Jan Martinek
 */
final class PasswordCast extends BaseCast
{
    public static function get($value, array $params = [])
    {
        return $value;
    }

    public static function set($value, array $params = [])
    {
        return password_hash($value, PASSWORD_DEFAULT);
    }
}
