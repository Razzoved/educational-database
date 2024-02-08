<?php

declare(strict_types=1);

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Config extends Entity
{
    protected $attributes = [
        'id'     => null,
        'value'  => null,
    ];

    protected $casts = [
        'id'    => 'string',
        'value' => 'string',
    ];
}
