<?php declare(strict_types=1);

namespace App\Persistence\Entities;

use CodeIgniter\Entity\Entity;

final class Property extends Entity
{
    protected $attributes = [
        'id' => null,
        'parent' => null,
        'value' => null,
        'priority' => null,
        'description' => null,
        // non-DB attributes
        'category' => null,
        'children' => null,
        'usage' => null,
    ];

    protected $casts = [
        'id' => 'int',
        'priority' => 'int',
        'description' => 'string',
        'category' => 'string',
        'children' => 'array',
        'usage' => 'int',
    ];
}
