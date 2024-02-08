<?php

declare(strict_types=1);

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Property extends Entity
{
    protected $attributes = [
        'id'          => null,
        'parent'      => null,
        'value'       => null,
        'priority'    => null,
        'description' => null,
        'category'    => null, // not part of db, but loaded
        'children'    => null, // not part of db (on demand)
        'usage'       => null, // not part of db (on demand)
    ];

    protected $casts = [
        'id'          => 'int',
        'priority'    => 'int',
        'description' => 'string',
        'category'    => 'string',
        'children'    => 'array',
        'usage'       => 'int',
    ];
}
