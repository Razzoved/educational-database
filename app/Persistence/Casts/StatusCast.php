<?php declare(strict_types=1);

namespace App\Persistence\Casts;

use CodeIgniter\Entity\Cast\BaseCast;

/**
 * Casts status of the entity for easier comparisons. Hides
 * the datababase string values behind an in-app enum.
 *
 * @author Jan Martinek
 */
final class StatusCast extends BaseCast
{
    public static function get($value, array $params = [])
    {
        return $value;
    }

    public static function set($value, array $params = [])
    {
        if (gettype($value) === Status::class) {
            return $value;
        }
        if (gettype($value) === 'string' && ($type = Status::tryFrom($value)) !== null) {
            return $type;
        }
        throw new \InvalidArgumentException('Invalid status value ' . $value);
    }
}

enum Status: string
{
    case INVALID = 'Invalid';
    case DRAFT = 'Draft';
    case PENDING = 'Pending review';
    case VISIBLE = 'Published';
}
